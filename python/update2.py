import time
import mysql.connector
from opensky_api import OpenSkyApi
from math import sin, cos, sqrt, atan2, radians

# creates a database connection
db = mysql.connector.connect(host = "localhost",           # database host pc
                                          user = "root",               # database username 
                                          password = "R@y6SrPjt",  # database user password
                                          port = "10060",          # port number
                                          db = "test")                 #name of the database to connect to

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

def finalUpdate(mylat, mylon, radius):
        cursor = db.cursor()
        cursor.execute("USE test")

        #clears all database columns
        cursor.execute("DELETE FROM final_aircraft;")

        #connects to api and gets state vectors
        #this statement has to be done everytime we want to pull the data 
        api = OpenSkyApi('User343', '0p3n$ky123!')
        states = api.get_states()

        x = 1
        
        for s in states.states:
                if (inRange(mylat, mylon, s, radius)):

                        #puts all the data into database row by row
                        cursor.execute("INSERT INTO final_aircraft(final_entry, final_lat, final_lon, final_vel, final_head, final_call, final_alt, final_icao24) VALUES (%s, %s, %s, %s, %s, %s, %s, %s);",(x, s.latitude, s.longitude, s.velocity, s.heading, s.callsign, s.geo_altitude, s.icao24))
                        x += 1
                else:
                        continue
        db.commit()

def initialUpdate(mylat, mylon, radius):
        cursor = db.cursor()
        cursor.execute("USE test")

        #clears all database columns
        cursor.execute("DELETE FROM aircrafts;")

        #connects to api and gets state vectors
        #this statement has to be done everytime we want to pull the data 
        api = OpenSkyApi('User343', '0p3n$ky123!')
        states = api.get_states()
        timeStamp = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(states.time))

        


        x = 1
        
        for s in states.states:
                if (inRange(mylat, mylon, s, radius)):
                        #puts all the data into database row by row
                        cursor.execute("INSERT INTO aircrafts(entry, latitude, longitude, velocity, heading, callsign, altitude, icao24, time) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s);",(x, s.latitude, s.longitude, s.velocity, s.heading, s.callsign, s.geo_altitude, s.icao24, timeStamp))
                        x += 1
                else:
                        continue
        db.commit()

#update counter
count = 1
while True:
        odd = count%2
        if(odd == 1):
                 initialUpdate(lat, lon, myRange)
                 print('Database updated ' , count)
        else:
                finalUpdate(lat, lon, myRange)
                print('Final Database updated ', count)
        count += 1       
        time.sleep(1.5)
        

	
