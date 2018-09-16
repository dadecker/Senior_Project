import time
import MySQLdb
from opensky_api import OpenSkyApi
from math import sin, cos, sqrt, atan2, radians

# creates a database connection
db = MySQLdb.connect(host = "localhost",           # database host pc
					  user = "root",               # database username 
					  password = "Southflorida8",  # database user password
					  db = "opensky")                 #name of the database to connect to

lat = 28.059489
lon = -82.412234
myRange = 60.0


def inRange(mylat, mylon, s,radius):
	R = 6373.0
	lat1 = radians(mylat)
	lon1 = radians(mylon)
	if (not s.latitude and not s.longitude):
		return False
	else:
		lat2 = radians(float(s.latitude))
		lon2 = radians(float(s.longitude))

	dlon = lon2 - lon1
	dlat = lat2 - lat1

	a = sin(dlat/2)**2 + cos(lat1) * cos(lat2) * sin(dlon / 2)**2
	c = 2 * atan2(sqrt(a), sqrt(1-a))

	distance = R * c
	if (distance <= radius):
		return True
	else:
		return False

def update(mylat, mylon, radius):
	cursor = db.cursor()
	cursor.execute("USE opensky")

	#clears all database columns
	cursor.execute("DELETE FROM aircraft;")

	#connects to api and gets state vectors
	#this statement has to be done everytime we want to pull the data 
	api = OpenSkyApi('User343', '0p3n$ky123!')
	states = api.get_states()

	x = 1
	
	for s in states.states:
		if (inRange(mylat, mylon, s, radius)):

			#puts all the data into database row by row
                        cursor.execute("INSERT INTO aircraft(entry, latitude, longitude, velocity, heading, callsign, geo_altitude, icao24) VALUES (%r, %s, %s, %s, %s, %s, %s, %r);",(x, s.latitude, s.longitude, s.velocity, s.heading, s.callsign, s.geo_altitude, s.icao24))
			x += 1
		else:
			continue
	db.commit()

#update counter
count = 1
while True:
	update(lat, lon, myRange)
	print('Database updated ' , count)
	count += 1
	time.sleep(1.5)
