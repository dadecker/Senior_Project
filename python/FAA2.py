import mysql.connector
import requests
import time
from urllib.request import urlopen
import urllib.parse
import json
from math import sin, cos, sqrt, atan2, radians


# connect to API
url = 'http://uas-faa.opendata.arcgis.com/datasets/6269fe78dc9848d28c6a17065dd56aaf_0.geojson'
#url = 'http://uas-faa.opendata.arcgis.com/datasets/9fed384137ba47189c37c3249694041e_0.geojson'
req = requests.get(url)
data = json.loads(req.text)
#print(req)
# connect to database
db = mysql.connector.connect(host = "localhost",
                     user = "root",
                     port = "10060",
                     password = "R@y6SrPjt",
                     db = "test")

# connect to table and clear contents
cursor = db.cursor()
cursor.execute("USE test")
cursor.execute("TRUNCATE TABLE test.buildings;")

print("Connected to database - updating table...")

# function for finding if airport is in range
def inRange(mylat, mylon, buildinglat, buildinglon):
    R = 6373.0
    lat1 = radians(mylat)
    lon1 = radians(mylon)
    if (not buildinglat and not buildinglon):
        return False
    else:
        if(not buildinglon):
            buildinglon = 0
        
        lat2 = radians(float(buildinglat))
        lon2 = radians(float(buildinglon))
       
    dlon = lon2 - lon1
    dlat = lat2 - lat1

    a = sin(dlat/2)**2 + cos(lat1) * cos(lat2) * sin(dlon / 2)**2
    c = 2 * atan2(sqrt(a), sqrt(1-a))

    distance = R * c
    if (distance <= 50):
    	return True
    else:
    	return False

# lat/long of USF library
mylat = 28.059489
mylon = -82.412234

def update(mylat, mylon):
    # index to keep track of airport in JSON
    index = 0
    # x is entry into table
    x = 1
    # add location coordinates to table only if within range
    while(index < len(data['features'])):
        buildinglon = data['features'][index]['properties']['LONGITUDE']
        buildinglat = data['features'][index]['properties']['LATITUDE']
        if (inRange(mylat, mylon, buildinglat, buildinglon)):
                cursor.execute("INSERT INTO test.buildings(entry, id, airport, latitude, longitude, height) VALUES (%s, %s, %s, %s, %s, %s);", (x, data['features'][index]['properties']['AIRPORTID'], data['features'][index]['properties']['ARPT_Name'], data['features'][index]['properties']['LATITUDE'], data['features'][index]['properties']['LONGITUDE'], data['features'][index]['properties']['CEILING']))
                x+=1
                index+=1
        else:
                index+=1
                continue

    db.commit()

update(mylat, mylon)
print('Database updated ')
db.close()
