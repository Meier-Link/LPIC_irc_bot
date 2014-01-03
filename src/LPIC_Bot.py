#!/usr/bin/env python
# -*- coding: utf-8 -*-

# Copyright 2010 Jérémie Balagna-Ranin <jeremie.balagna@autistici.org>
# LPIC_Bot.py
#   A bot to run LPIC quizz on your favorite irc chat
#
# This is free and unencumbered software released into the public domain.
# See LICENCE file for more information

import sqlite3
import irclib
import ircbot

import time
from random import randrange, randint, sample
from unicodedata import normalize
from datetime import datetime

alphabet = 'abcdefghijklmnopqrstuvwxyz'

# query = ...
# params = (foo, bar, ...)
# self.cu.execute(query, params)
# r = self.cu.fetchone() || self.cu.fetchall()
# r['<col_name>']
class LPIC_DB:
  def __init__(self):
    self.NAME         = 'lpic_quizz.db'
    self.QU_TABLE     = 'question'
    self.QU_FIELDS    = 'q_id, q_txt, q_lvl, q_lang'
    self.AN_TABLE     = 'answer'
    self.AN_FIELDS    = 'a_id, a_q_id, a_is_right, a_txt'
    self.LVL_TABLE    = 'level'
    self.LVL_FIELDS   = 'le_id, le_name'
    self.LNG_TABLE    = 'lang'
    self.LNG_FIELDS   = 'la_id, la_short'
    self.U_TABLE      = 'user'
    self.U_FIELDS     = 'u_id, u_pseudo, u_start, u_score'
    self.co = sqlite3.connect(self.NAME)
    self.co.row_factory = sqlite3.Row
    self.cu = self.co.cursor()
  
  def convert(self, i):
    if isinstance(i, int):
      return i
    else:
      return normalize('NFKD', i).encode('ascii', 'ignore')
  
  def insert(self, query):
    self.cu.execute(query)
    self.co.commit()
  
  def get_score(self, u_pseudo):
    # verify if user already exist
    if len(u_pseudo) > 255:
      u_pseudo = u_pseudo[:255]
    query = "SELECT " + self.U_FIELDS + " FROM " + self.U_TABLE + " WHERE u_pseudo=?"
    self.cu.execute(query, (u_pseudo,))
    r = self.cu.fetchone()
    if r is not None:
      return r['u_score']
    else:
      return 0
  
  def upgrade_user(self, u_pseudo):
    # verify if user already exist
    if len(u_pseudo) > 255:
      u_pseudo = u_pseudo[:255]
    query = "SELECT " + self.U_FIELDS + " FROM " + self.U_TABLE + " WHERE u_pseudo=?"
    self.cu.execute(query, (u_pseudo,))
    r = self.cu.fetchone()
    if r is not None:
      new_score = r['u_score'] + 1
      query = "UPDATE " + self.U_TABLE + " SET u_score=? WHERE u_pseudo=?"
      self.cu.execute(query, (new_score, u_pseudo))
    else:
      tgt_fields = self.U_FIELDS.split(', ')
      tgt_fields.remove('u_id')
      query = """INSERT INTO """ + self.U_TABLE + """ (""" + ', '.join(tgt_fields) + """) VALUES (?, ?, ?);"""
      self.cu.execute(query, (u_pseudo, datetime.now().strftime('%Y-%m-%d %X'), 1))
    self.co.commit()
  
  def select_random(self, lvl=False, lng=False):
    query = "SELECT " + self.QU_FIELDS + ", " + self.LVL_FIELDS + ", " + self.LNG_FIELDS + " FROM " + self.QU_TABLE 
    if lvl and lng:
      query += " INNER JOIN " + self.LVL_TABLE + " ON q_lvl == le_id AND le_name == ? " \
        + " INNER JOIN " + self.LNG_TABLE + " ON q_lang == la_id AND la_short == ?;"
      self.cu.execute(query, (lvl, lng))
    elif lvl:
      query += " INNER JOIN " + self.LVL_TABLE + " ON q_lvl == le_id AND le_name == ? " \
        + " INNER JOIN " + self.LNG_TABLE + " ON q_lang == la_id;"
      self.cu.execute(query, (lvl,))
    elif lng:
      query += " INNER JOIN " + self.LVL_TABLE + " ON q_lvl == le_id " \
        + " INNER JOIN " + self.LNG_TABLE + " ON q_lang == la_id AND la_short == ?;"
      self.cu.execute(query, (lng,))
    else:
      query += " INNER JOIN " + self.LVL_TABLE + " ON q_lvl == le_id " \
        + " INNER JOIN " + self.LNG_TABLE + " ON q_lang == la_id;"
      self.cu.execute(query)
    rows = self.cu.fetchall()
    selected = sample(rows, 1)[0]
    question = {'q': self.convert(selected['q_txt']), 'lng': selected['la_short'], 'lvl': selected['le_name'], 'a': {}, 'r': ''}
    query = "SELECT " + self.AN_FIELDS + " FROM " + self.AN_TABLE + " WHERE a_q_id=?"
    self.cu.execute(query, (selected['q_id'],))
    rows = self.cu.fetchall()
    cpt = 0
    for row in rows:
      if row['a_is_right']: question['r'] += alphabet[cpt]
      question['a'][alphabet[cpt]] = self.convert(row['a_txt'])
      cpt += 1
    return question


class LPIC_Bot(ircbot.SingleServerIRCBot):
  def __init__(self):
    ircbot.SingleServerIRCBot.__init__(self, [("irc.freenode.net", 6667)], "LPIC_Bot", "Bot poseur de question de LPIC")
    self.CHAN = '#lpic-fr'
    self.whitelist = ["Meier_Link", "meier_link"]
    self.cmds = {
      'help':  "Afficher l'aide (les paramètres permettent d'afficher l'aide seulement pour les commandes listées)",
      'score': "Pour afficher le score d'une personne. Tu peux afficher le score pour plusieurs personnes en listant leur pseudo",
      'start': "Ça, c'est pour me demander de poser une question. Vous pouvez passer '101', '102', ... en paramètre pour filtrer les questions par niveau",
      'test':  "Tu penses avoir la réponse ? Rajoutes-la après cette commande (s'il y en a plusieurs, ne met pas d'espace)",
      'answer': """T'en a marre de chercher (ou t'es un gros flemmard ... Ce qui revient au même), cette commande te permettra de connaître la réponse"""}
    self.db = LPIC_DB()
    self.current = None
  
  def get_question(self, lvl=False, lng=False):
    if self.current is None:
      self.current = self.db.select_random(lvl, lng)
    else:
      self.current = self.db.select_random(lvl, lng)
  
  def check_answer(self, u, a):
    if a == self.current['r']:
      self.current = None
      self.db.upgrade_user(u)
      return True
    else:
      return False
  
  def usage(self, serv, canal, params):
    serv.privmsg(canal, 'Usage: !<cmd> [param [param [...]]]')
    if len(params) > 0:
      for param in params:
        if param in self.cmds.keys():
          if param == 'help':
            serv.privmsg(canal, "  Juste comme ça, je te signal que t'es déjà dans l'aide, là ... -_-")
          else:
            serv.privmsg(canal, '  ' + param + ': ' + self.cmds[param])
    else:
      for k in self.cmds.keys():
        serv.privmsg(canal, k + ': ' + self.cmds[k])
  
  def display_question(self, serv, canal):
    if self.current is None:
      serv.privmsg(canal, "Aucune question trouvée ! Faudra penser à alimenter la bdd (ou checker la question oO) ...")
    else:
      serv.privmsg(canal, "Voici la question (niveau " + str(self.current['lvl']) + ") : " + self.current['q'])
      keys = self.current['a'].keys()
      keys.sort()
      for k in keys:
        serv.privmsg(canal, "Réponse " + k + " : " + self.current['a'][k])
  
  def on_welcome(self, serv, ev):
    canal = ev.target()
    serv.join(self.CHAN)
    serv.privmsg(canal, "Bonjour tout le monde !")
    
  def on_pubmsg(self, serv, ev):
    auteur = irclib.nm_to_n(ev.source())
    canal = ev.target()
    #arg1 = ev.arguments()[0].lower()
    arg1 = ev.arguments()[0]
    print auteur + canal + ' : ' + arg1 + '\n'
    
    args = arg1.split(" ")
    if len(args) > 1:
      params = [args[i] for i in range(1, len(args))]
    else:
      params = []
    if args[0][0] == '!':
      print('args: ' + str(len(args)))
      cmd = args[0][1:]
      if cmd in self.cmds.keys():
        # Display help
        if cmd == 'help':
          self.usage(serv, canal, params)
        # Start a new quizz
        elif cmd == 'start':
          lvl = False
          if len(args) > 1: lvl = args[1]
          if self.current is None: self.current = self.db.select_random(lvl)
          else: serv.privmsg(canal, "Vous avez déjà demandé une question (" + auteur + ", spice de boulet !)")
          self.display_question(serv, canal)
        # Check an answer
        elif cmd == 'test':
          if self.current is not None:
            if (len(args) == 2):
              if self.check_answer(auteur, args[1]):
                serv.privmsg(canal, "Bravo " + auteur + " ! C'était bien ça !")
                serv.privmsg(canal, "Prêt pour une nouvelle question ?")
              else:
                serv.privmsg(canal, "IOU LOUUSE ! Traille eugééne !")
            else:
              serv.privmsg(canal, "T'as le droit à une ET une seule réponse par tentative")
          else:
            serv.privmsg(canal, "Non mais t'es sérieux là ? Tu ne m'a pas encore posé de question -_-'")
        # Send the right answer (and reset quizz)
        elif cmd == 'answer':
          if self.current is not None:
            serv.privmsg(canal, "Kwa ?! T'abandonne déjà ?! pffff ... Bon, bah la bonne réponse était : " + self.current['r'])
            serv.privmsg(canal, "Prêt pour une nouvelle question ?")
            self.current = None
          else:
            serv.privmsg(canal, "Non mais t'es sérieux là ? Tu ne m'a pas encore posé de question -_-'")
        elif cmd == 'score':
          if len(params) > 0:
            to_display = 'Scores : '
            for pseudo in params:
              score = self.db.get_score(pseudo)
              to_display += pseudo + "=" + str(score) + '; '
            serv.privmsg(canal, to_display)
          else:
            score = self.db.get_score(auteur)
            serv.privmsg(canal, auteur + ": " + str(score))
  
  def on_action(self, serv, ev):
    pass
  
  def on_kick(self, serv, ev):
    serv.join(self.CHAN)
    serv.action(canal, "Bah non. Tu peux pas me sortir (non mais croyait quoi, l'aut' !)")
    
if __name__ == "__main__":
  LPIC_Bot().start()


