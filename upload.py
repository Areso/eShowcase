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


skus     = []
names    = []
groups   = []
photos   = []
descs    = []
prices   = []
qtys     = []

for x in range(0, line_count):
    temp_list = tables_lines[x].split(';')
    #print(temp_list)
    skus.append(temp_list[0].lstrip())
    names.append(temp_list[1])
    groups.append(temp_list[2])
    photos.append(temp_list[3])
    descs.append(temp_list[4])
    prices.append(temp_list[5])
    qtys.append(temp_list[6])


mydb = mysql.connector.connect(
  host=conf_list[2],
  user=conf_list[0],
  passwd=conf_list[1],
  database=conf_list[4]
)
mycursor = mydb.cursor()

sql = "DELETE FROM goods WHERE id_group='"+groups[1]+"'"
print(sql)
mycursor.execute(sql)
mydb.commit()

sql = "INSERT INTO goods (item_sku, item_name, id_group, photo, description, price, qty) VALUES (%s, %s, %s, %s, %s, %s, %s)"
val = []

for x in range(1,line_count):
    val.append(tuple((skus[x], names[x], groups[x], photos[x], descs[x], prices[x], qtys[x])))

#print(val)
mycursor.executemany(sql, val)
mydb.commit()
print(mycursor.rowcount, "was inserted.")