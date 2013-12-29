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

class LPIC_Bot(ircbot.SingleServerIRCBot):
  def __init__(self):
    ircbot.SingleServerIRCBot.__init__(self, [("irc.freenode.net", 6667)], "LPIC_Bot", "Bot poseur de question de LPIC")
    self.CHAN = '#lpic-fr'
    self.DB_NAME = 'lpic_quizz.db'
    self.DB_QUIZZ_TABLE  = 'question'
    self.DB_QUIZZ_FIELDS = ['q_id', 'q_lvl', 'q_quest', 'q_ans_a', 'q_ans_b', 'q_ans_c', 'q_ans_d', 'q_right_ans']
    self.quizz_size = None
    self.DB_USER_TABLE   = 'user'
    self.DB_USER_FIELDS  = ['u_id', 'u_pseudo', 'u_start', 'u_score']
    self.co = sqlite3.connect(self.DB_NAME)
    self.co.row_factory = sqlite3.Row
    self.cu = self.co.cursor()
    self.whitelist = ["Meier_Link", "meier_link"]
    self.cmds = {
      'help':  "Afficher l'aide (les paramètres permettent d'afficher l'aide seulement pour les commandes listées)",
      'score': "Pour afficher le score d'une personne. Tu peux afficher le score pour plusieurs personnes en listant leur pseudo",
      'start': "Ça, c'est pour me demander de poser une question. Vous pouvez passer '101', '102', ... en paramètre pour filtrer les questions par niveau",
      'test':  "Tu penses avoir la réponse ? Rajoutes-la après cette commande (s'il y en a plusieurs, ne met pas d'espace)",
      'answer': """T'en a marre de chercher (ou t'es un gros flemmard ... Ce qui revient au même), cette commande te permettra de connaître la réponse"""}
    self.current = None
  
  ### DB specific methods ###
  def convert(self, i):
    if isinstance(i, int):
      return i
    else:
      return normalize('NFKD', i).encode('ascii', 'ignore')
  
  def db_insert(self, query):
    self.cu.execute(query)
    self.co.commit()
  
  def db_get_score(self, u_pseudo):
    # verify if user already exist
    if len(u_pseudo) > 255:
      u_pseudo = u_pseudo[:255]
    query = "SELECT " + ', '.join(self.DB_USER_FIELDS) + " FROM " + self.DB_USER_TABLE + " WHERE u_pseudo=?"
    print query + '; ' + u_pseudo
    self.cu.execute(query, (u_pseudo,))
    r = self.cu.fetchone()
    if r is not None:
      return r['u_score']
    else:
      return 0
  
  def db_upgrade_user(self, u_pseudo):
    # verify if user already exist
    if len(u_pseudo) > 255:
      u_pseudo = u_pseudo[:255]
    query = "SELECT " + ', '.join(self.DB_USER_FIELDS) + " FROM " + self.DB_USER_TABLE + " WHERE u_pseudo=?"
    self.cu.execute(query, (u_pseudo,))
    r = self.cu.fetchone()
    if r is not None:
      r['u_score'] += 1
      query = "UPDATE " + self.DB_USER_TABLE + " SET u_score=? WHERE u_pseudo=?"
      self.cu.execute(query, (r['u_score'], u_pseudo))
    else:
      tgt_fields = self.DB_USER_FIELDS
      tgt_fields.remove('u_id')
      query = """INSERT INTO """ + self.DB_USER_TABLE + """ (""" + ', '.join(tgt_fields) + """) VALUES (?, ?, ?);"""
      self.cu.execute(query, (u_pseudo, datetime.now().strftime('%Y-%m-%d %X'), 1))
    self.co.commit()
  
  def db_select_random(self, lvl=False):
    if lvl:
      query = "SELECT q_id, l_name, q_quest, q_ans_a, q_ans_b, q_ans_c, q_ans_d, q_right_ans FROM question INNER JOIN level ON q_lvl == l_id AND l_name == ?;"
      self.cu.execute(query, (lvl,))
    else:
      query = "SELECT q_id, l_name, q_quest, q_ans_a, q_ans_b, q_ans_c, q_ans_d, q_right_ans FROM question INNER JOIN level ON q_lvl == l_id;"
      self.cu.execute(query)
    rows = self.cu.fetchall()
    if (len(rows) < 1): return None
    r = sample(rows, 1)[0]
    if r is not None:
      r = [self.convert(i) for i in r]
      print r
      return {'lvl': r[1], 'q': r[2], 'a': r[3], 'b': r[4], 'c': r[5], 'd': r[6], 'r': r[7]}
    else:
      return None
  
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
  
  def check_answer(self, u, a):
    if a == self.current['r']:
      self.current = None
      self.db_upgrade_user(u)
      return True
    else:
      return False
  
  def on_welcome(self, serv, ev):
    canal = ev.target()
    serv.join(self.CHAN)
    serv.privmsg(canal, "Bonjour tout le monde !")
    
  def on_pubmsg(self, serv, ev):
    auteur = irclib.nm_to_n(ev.source())
    canal = ev.target()
    arg1 = ev.arguments()[0].lower()
    print auteur + canal + ' : ' + arg1 + '\n'
    
    args = arg1.split(" ")
    if len(args) > 1:
      params = [args[i] for i in range(1, len(args)-1)]
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
          if self.current is None: self.current = self.db_select_random(lvl)
          else: serv.privmsg(canal, "Vous avez déjà demandé une question (" + auteur + ", spice de boulet !)")
          if self.current is None:
            serv.privmsg(canal, "Aucune question trouvée ! Faudra penser à alimenter la bdd (ou checker la question oO) ...")
          else:
            serv.privmsg(canal, "Voici la question (niveau " + str(self.current['lvl']) + ") : " + self.current['q'])
            serv.privmsg(canal, "Réponse a : " + self.current['a'])
            serv.privmsg(canal, "Réponse b : " + self.current['b'])
            serv.privmsg(canal, "Réponse c : " + self.current['c'])
            serv.privmsg(canal, "Réponse d : " + self.current['d'])
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
              score = self.db_get_score(pseudo)
              to_display += pseudo + "=" + str(score) + '; '
            serv.privmsg(canal, to_display)
          else:
            score = self.db_get_score(auteur)
            serv.privmsg(canal, auteur + ": " + str(score))
  
  def on_action(self, serv, ev):
    pass
  
  def on_kick(self, serv, ev):
    serv.join("self.CHAN")
    serv.action(canal, "Bah non. Tu peux pas me sortir (non mais croyait quoi, l'aut' !)")
    
if __name__ == "__main__":
  LPIC_Bot().start()


