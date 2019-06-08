#!/usr/bin/env python3
#This project licensed under MIT License
#author:  Gladyshev Anton 
#contact: Returnstrike [at] ya.ru
import argparse
import sys
from time import sleep
import mysql.connector

parser = argparse.ArgumentParser()
parser.add_argument("u", help="filename without .csv extension", type=str)
args = parser.parse_args()
filename = args.u

fconf = open('config.txt', 'r')
tconf = fconf.read()
fconf.close()
conf_list = tconf.split('\n')

username = conf_list[0]
password = conf_list[1]
hostname = conf_list[2]
dbport   = conf_list[3]
database = conf_list[4]

# to use mysql.connector you should run pip3 install mysql-connector!

filename = filename + '.csv'
ftab = open(filename, 'r')
tables = ftab.read()
ftab.close()
tables_lines = tables.split('fFx')
line_count = len(tables_lines)-1
#print(line_count)


ids      = []
names    = []

for x in range(0, line_count):
    temp_list = tables_lines[x].split(';')
    #print(temp_list)
    ids.append(temp_list[0].lstrip())
    names.append(temp_list[1])


mydb = mysql.connector.connect(
  host=conf_list[2],
  user=conf_list[0],
  passwd=conf_list[1],
  database=conf_list[4]
)
mycursor = mydb.cursor()

sql = "DELETE FROM groups"
print(sql)
mycursor.execute(sql)
mydb.commit()

sql = "INSERT INTO groups (id_group, name_group) VALUES (%s, %s)"
val = []

for x in range(1,line_count):
    val.append(tuple((ids[x], names[x])))

#print(val)
mycursor.executemany(sql, val)
mydb.commit()
print(mycursor.rowcount, "was inserted.")
