import requests
import aspen
import time
import json


HEADERS = {"user-agent": "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.87 Safari/537.36"}
OVERALL_UPDATE_DELAY = 5
PER_USER_UPDATE_DELAY = 5
BASE_URL = ""
UPDATE_MSG_AUTH = ""
LOAD_USERS_AUTH = ""


class user:
	def __init__(self, phone, username, password):
		self.phone = phone
		self.username = username
		self.password = password

		
		
def dict_to_pretty_string(inputDict):
	returnString = ""
	for key in sorted(inputDict.keys()):
		returnString += key + "  " + inputDict[key] + "\n"
		
	return returnString[:-1]
		
	

def post_dict_to_server(infoDict, phoneNum):
	prettyString = dict_to_pretty_string(infoDict)
	
	payload = {"phone": phoneNum, "msg": prettyString}
	
	url = BASE_URL + "updateMsg.php?auth=" + UPDATE_MSG_AUTH
	resp = requests.post(url, headers=HEADERS, data=payload)
	
	print(resp.text)
	

def load_users():
	urlString = BASE_URL + "python_caller.php?auth=" + LOAD_USERS_AUTH
	response = requests.get(urlString, headers=HEADERS)
	jsonResp = json.loads(response.text)
	
	list = []
	for currentUser in jsonResp:
		newUser = user(currentUser['phone'], currentUser['user'], currentUser['pass'])
		list.append(newUser)
		
	return list


while True:
	userList = load_users()
	print("currently updating", len(userList), "users")
	for currentUser in userList:
		print("testing:", currentUser.username)
		userAspen = aspen.Aspen()
		userAspen.set_credentials(currentUser.username, currentUser.password)

		if userAspen.login():
			gradesDict = userAspen.scrape_grades()
			post_dict_to_server(gradesDict, currentUser.phone)
		else:
			print("bad login from user", currentUser.username)
		time.sleep(PER_USER_UPDATE_DELAY)
	time.sleep(OVERALL_UPDATE_DELAY)





















