import requests
import json
import urllib.parse
from urllib.request import urlopen
from fleetmonger import Fleetmonger

#https://pypi.python.org/pypi/fleetmonger/0.0.4
#url = 'https://www.fleetmon.com/api/p/personal-vi/vessels_terrestrial/?username=FleetUser01&api_key=F133tY@M0n!'
#req = requests.get(url)
#data = json.loads(req.decode("utf-8"))
#data = req.json()

#resp_text = urllib.request.urlopen(url).read().decode('UTF-8')
#data = json.loads(resp_text)

fm = Fleetmonger('FleetUser01','F133tY@M0n!')

fleet = fm.myfleet()

for ship in fleet:
        print(ship.name, ship.destination)

#print(data)
