#!/usr/bin/env python2
# -*- coding: utf-8 -*-

# Copyright 2010 Jérémie Balagna-Ranin <jeremie.balagna@autistici.org>
# update_db.py
#   A new way to update sqlite DB from lpi.txt file
#
# This is free and unencumbered software released into the public domain.
# See LICENCE file for more information
#
#
# Some important note
#   lpi.txt must use the following form:
#   q<number> - <lpic_level>
#   <Question_text>
#     <Answer_0><if is_right ? 1 : 0>
#     <Answer_1><if is_right ? 1 : 0>
#     <...>
#     <Answer_N><if is_right ? 1 : 0>
#

import sqlite3
import sys

from unicodedata import normalize

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
    self.co = sqlite3.connect(self.NAME)
    self.co.row_factory = sqlite3.Row
    self.cu = self.co.cursor()
    self.qu = ''
    self.lvl = ''
    self.lng = ''
    self.an = []
  
  def _insert(self, query, params=None):
    self.cu.execute(query)
    self.co.commit()
  
  def _select_multi(self, query, params=None):
    if params is None:
      self.cu.execute(query)
    elif isinstance(p, tuple):
      self.cu.execute(query, params)
    else:
      print "params invalid type"
      sys.exit(1)
    return self.cu.fetchall()
    
  def _select_one(self, query, params=None):
    if params is None:
      self.cu.execute(query)
    elif isinstance(p, tuple):
      self.cu.execute(query, params)
    else:
      print "params invalid type"
      sys.exit(1)
    return self.cu.fetchone()
  
  def get_last_q_id(self):
    query = "SELECT " + self.QU_FIELDS + " FROM " + self.QU_TABLE + " ORDER BY q_id DESC LIMIT 1"
    r = self._select_one(query)
    if r is not None:
      return r[0]
    else:
      return 0
  
  def set_question(self, q):
    '''q format must be
      [<question>, <level>, <language>, <answers>]
    <answers> must be
      [[<txt>, <is_right>], ...]
    '''
    self.qu = q[0]
    print self.qu
    self.lvl = q[1]
    print self.lvl
    self.lng = q[2]
    print self.lng
    self.an = q[3]
    print self.an
  
  def run(self):
    # insert question
    query

class Updater:
  def __init__(self):
    self.infile = 'lpi.txt'
    self.db = LPIC_DB()
  
  def _parse(self, lex):
    cnt = 0
    question = ''
    answers = []
    right_one = ''
    lvl = ''
    lng = ''
    q_num = ''
    study = False
    for l in lex:
      if cnt == 0:
        if 'ok' not in l:
          study = True
          lvl = l.split(' - ')[1]
          lng = l.split(' - ')[2]
          q_num = l.split(' - ')[0]
        else:
          break
      if study:
        if cnt <= 1:
          question = l.strip()
        else:
          an = l.strip().split(' - ')
          if an[-1] == '0' or an[-1] == '1':
            answers.append([' '.join(an[0:-1]), an[-1]])
          else:
            print '[ERROR] Parse error in question ' + q_num + ' !'
            print l
            study = False
            break
          right_one = l.strip()
      cnt += 1
    
    if study:
      self.db.set_question([question, lvl, lng, answers])
  
  def run(self):
    f = open(self.infile, 'r')
    lex = []
    for line in f:
      if line.strip() != '' and line[0] != '#':
        #print line.strip()
        lex.append(line.strip())
      else:
        self._parse(lex)
        lex = []
    f.close()

if __name__ == "__main__":
  cls = Updater()
  cls.run()
