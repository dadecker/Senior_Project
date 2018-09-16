import time
import mysql.connector
import requests
from urllib.request import urlopen
import urllib.parse
import json
from math import sin, cos, sqrt, atan2, radians

# connect to API

# lat/long of USF library
mylat = 28.059489
mylon = -82.412234

url = 'http://public-api.adsbexchange.com/VirtualRadar/AircraftList.json?lat=' + str(mylat) + '&lng=' + str(mylon) + '&fDstL=0&fDstU=97'
req = requests.get(url)
data = json.loads(req.text)


# creates a database connection
db = mysql.connector.connect(host = "localhost",           # database host pc
                                          user = "root",               # database username 
                                          password = "R@y6SrPjt",  # database user password
                                          port = "10060",          # port number
                                          db = "test")                 #name of the database to connect to

# connect to table and clear contents
cursor = db.cursor()
cursor.execute("DELETE FROM test.flight2;")

def getLat(i):
	if(data['acList'][i]['Lat'] is not None):
		return data['acList'][i]['Lat']
	else:
		return None
def getLon(i):
	if(data['acList'][i]['Long'] is not None):
		return data['acList'][i]['Long']
	else:
		return None

def getVelocity(i):
	if('Spd' not in data['acList'][i]):
		return None
	else:
		return data['acList'][i]['Spd'] * 0.514444 # API given speed is in knots, must convert to meters per second

def getHeading(i):
	if(data['acList'][i]['Trak'] is not None):
		return data['acList'][i]['Trak']
	else:
		return None
def getCallsign(i):
	if('Call' not in data['acList'][i]):
		return None
	return data['acList'][i]['Call']

def getAltitude(i):
	if(data['acList'][i]['Alt'] is not None):
		return data['acList'][i]['Alt']
	else:
		return None

def update():
	x = 1
	i = 0
	while(i < len(data['acList'])):
		cursor.execute("INSERT INTO test.flight2(entry, latitude, longitude, velocity, heading, callsign, altitude, icao24, time, onground) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s);", (x, getLat(i), getLon(i), getVelocity(i), getHeading(i), getCallsign(i), getAltitude(i), data['acList'][i]['Icao'], 0, 0))
		x+=1
		i+=1

update()
db.commit()
cursor.close()
db.close()
