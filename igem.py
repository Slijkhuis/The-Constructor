from operator import itemgetter
from datetime import datetime
import string
import MySQLdb
import sys
import re
import itertools
from itertools import permutations

#Creating the queries obtaining the data and do all kinds of fancy stuff with it.
#If you started reading here, you should move down to the main() module at the very bottom of this script
def Querymaker(BioBricks,count,subpartlists,part_id_dict,query_settings,rfcstatus):
	trueBricks = []
	brickIDs = []
	#As the database works with IDs instead of BBa_X, we need to obtain the Id of every brick.
	for brick in BioBricks:
		#The query selects the part_id which is the part identifier of the brick that is given for example BBa_123456
		query = "SELECT part_id FROM brick WHERE part_name = '%s';" % (brick)
		#Firing the query and the part_id is obtained
		part_id = SQL(query)
		if len(part_id) == 0: 
			#If nothing was returned, the brick does not exists in the database and the program stops.
			#It is an option to replace that brick then by BBa_X but this is something that needs to be discussed.
			error = 'The following brick did not exist: '+brick+'\nTo continue please replace this brick\nand run the program again'
			#Sending the message to the outputFail module
			outputFailMessage(error)
			#Program exits
			sys.exit()
		#Now the IDs of all super parts are obtained
		brickIDs.append(str(part_id[0][0]))
	#Now it is time to denature the biobricks containing the TUs (TU1-TU2)
	#To do this we loop through every brick to see if there are any subparts connected to it.
	#This is checked in the superpart table in the database. 
	for brickID in brickIDs:
		#Query to achieve this
		query = "SELECT brick_part_id,brick_subpart_id,brick_location FROM superpart,brick WHERE brick_part_id = %s AND part_id=brick_part_id %s ORDER BY brick_location;" % (brickID,'')
		#Firing query
		superbricks = SQL(query)
		#When only 1 ID is returned, this means that the brick does not contain any subparts
		if len(superbricks) == 1:
			#This is the final part
			trueBricks.append(str(superbricks[0][0]))
		else:
			#These bricks contain subbricks
			subbricks = SQL(query) #This is not needed... Ignore this line for now.
			if len(subbricks) == 1:
				#This will never occur, ignore this line too. Dont comment it out yet, as it needs to be tested first.
				print "Something is wrong?"
			for subbrick in subbricks:
				#Now we loop through all the subbricks of that particular superpart
				if subbrick[2] == 0:
					#to skip the first hit, which is always a redirection to itself.
					pass
				else:
					#All the other parts we need to obtain some information from them
					#The superpart,subpart and the location in this superpart
					query = "SELECT brick_part_id,brick_subpart_id,brick_location FROM superpart,brick WHERE brick_part_id = %s AND part_id=brick_part_id %s ORDER BY brick_location;" % (subbrick[1],'')
					subpartbricks = SQL(query)
					if len(subpartbricks) == 1:
						trueBricks.append(str(subpartbricks[0][1]))

	#Small note to self, should do a print here once, to show the storage as it might make things clearer.
	#print trueBricks
	
	#Puzzle starts#
	#Renaming convention
	finalParts = trueBricks
	#The list of parts are glued together with an -
	finalPartscheck = '-'.join(finalParts)	

	##Only the first time###
	if count == 1:
		#A selectall the superparts containing any of the brick_part_id's from the finalParts list.
		#print "[DEBUGGIN]"
		query = "SELECT * FROM superpart WHERE brick_part_id IN (SELECT brick_part_id FROM superpart,brick WHERE brick_subpart_id IN (%s) AND part_id=brick_part_id %s AND rfc REGEXP 'RFC10\\\+|RFC23\\\+|RFC25\\\+') ORDER BY brick_part_id" % (','.join(finalParts),query_settings)
		#print query
		#SELECT * FROM superpart WHERE brick_part_id IN (SELECT brick_part_id FROM superpart,brick WHERE brick_subpart_id IN (162,145) AND part_id=brick_part_id  AND part_results IN ('None') AND best_quality IN ('Confirmed') AND part_status IN ('Available')) ORDER BY brick_part_id

		candidates = SQL(query)
		candidates = list(set(candidates)) #Not sure if duplicates are present thus if so, removed...
		#All the candidates with there subparts are then send of to the candidatesSQL module
		subpartlists,part_id_dict = candidatesSQL(candidates,finalPartscheck,query_settings)
	############
	
	#Check if order is good and remove all smaller bricks#
	subpartlists = list(set(subpartlists)) #Removes duplicates
	for subpartlist in enumerate(subpartlists):
		if subpartlist[1] not in finalPartscheck:
			subpartlists[subpartlist[0]] = ''
	
	testlist = []
	partlocation = {}
	for s in subpartlists:
		if len(s) != 0:
			hit = re.finditer(s,finalPartscheck)
			for h in hit:
				location = h.span()
				if s not in partlocation.keys():
					partlocation[s] = [location]
				else:
					value = partlocation[s]
					value.append(location)
					partlocation[s] = value
	#Now a dictionary is made with the location(s) of every brick
	for key in partlocation.keys():
		value = partlocation[key]

	route = showoutput(part_id_dict,partlocation,finalPartscheck,query_settings,rfcstatus)
	return subpartlists,part_id_dict,route

def candidatesSQL(candidates,finalPartscheck,query_settings):
	#All candidates are obtained, and stored.
	#Variable creation
	subpartlists,part_id_dict = [], {}
	candidatelist = []
	#All candidates are sorted by super part and by location in this superpart (a print might be useful here too.)
	candidates = sorted(candidates, key = lambda x: (x[0], x[2]))
	key = ''
	subparts = []
	for candidate in candidates:
		#Looping through every candidate to obtain the superpart, subpart, location
		if candidate[0] != key:
			#If the superpart does not contain the same name as the key, this means we just hit a new part.
			if len(subparts) != 0:
				#The very first loop this one is ignored
				subpartlists,part_id_dict = subpartcheck(subparts,finalPartscheck,subpartlists,part_id_dict)
			#because the key and the candidate are not the same, the key is now made the same as the candidate.
			key = candidate[0]
			#resetting the subparts as we just found a new candidate, we want to empty the list
			subparts = []
			subparts.append(candidate) #The first subpart is always the superpart (for the sake of simplicity in coding)
		else:
			#If the candidate[0] is equally to the key then we are still working with the same superpart and all information is appended.
			subparts.append(candidate)
	return subpartlists,part_id_dict

#When in the previous function a change occured, thus a new candidate was found. All the information of the previous candidate was obtained and sent to this function			
#Question to myself, what about the very last part?
def subpartcheck(subparts,finalPartscheck,subpartlists,part_id_dict):
	#Variable initialisation
	ALLPARTS = {}
	#Status variable is created to get a grip on if thins are true or not.
	status = True
	#Looping through all the subparts skipping the first one as this is the superpart information
	for subpart in subparts[1:]:
		#If this subpart is not present in our denatured user input it is not needed and discarded
		#Note to self: If this is possible with SQL that would speed up things slightly
		if str(subpart[1]) not in finalPartscheck:
			#And this causes the status to set on False
			status = False
			break
	#However, if the status remains True it means that the subparts are all present in the denatured FGMs
	#Now the parts are stored in a dictionary and in a list
	if status == True:
		key = subparts[0][0]
		subpartcheck = []
		if len(subparts) == 1: #Means it contains no subparts
			subpartcheck.append(str(subparts[0][0]))
		for subpart in subparts[1:]: #it does contain subparts and they are all added
			subpartcheck.append(str(subpart[1]))
		#List conversion to string with - separation
		subpartlists.append('-'.join(subpartcheck))
		#Storage in dictionary
		part_id_dict[str(key)] = '-'.join(subpartcheck)
	return subpartlists,part_id_dict
		
def SQLbrickmaker(newbrick):
	query = "SELECT * FROM superpart WHERE brick_subpart_id IN (%s);" % (','.join(newbrick))
	datas = SQL(query)
	bricklist = []
	brickdict = {}
	for data in datas:
		if data[2] < len(newbrick)+1:
			if newbrick.index(str(data[1]))+1 == data[2]: # == location check?
				if str(data[0]) in brickdict.keys():
					value = brickdict[str(data[0])]
					value = value + 1
					brickdict[str(data[0])] = value
				else:
					brickdict[str(data[0])] = 1
	for key in brickdict.keys():
		if brickdict[key] == len(newbrick):
			#Its a candidate!
			query = "SELECT * FROM superpart WHERE brick_part_id = %s ORDER BY brick_location" % (str(key))
			subparts = len(SQL(query))
			if subparts-1 == len(newbrick):#As the brick itself is also added!
				return [key,brickdict[key]]

def SQL(query):
	try:
		#Database information
		con = MySQLdb.connect('127.0.0.1', 'username', 'password', 'databasename');	
		#pre-connecting to it
		cur = con.cursor()
		#make the connection and fire the query
		cur.execute(query)
		#obtain all the information
		data = cur.fetchall()
		#Send the information back to the line of code that called this function.
		return data
	except Exception, e: 
		print repr(e)
		pass

def find_route(goal, paths, route=None):
	   if not route:
		  route = []
	   if not goal:
		  return route
	   longest = sorted((i for i in paths if goal.startswith(i[1])), key=lambda x: len(x[1]), reverse=True)[0]
	   goal = goal[len(longest[1]):]
	   if goal: #account for the dash if goal still exists
		  goal = goal[1:]
	   route.append(longest)
	   return find_route(goal, paths, route)

###### THE VISUALIZING PART ######
def showoutput(part_id_dict,partlocation,goal,query_settings,rfcstatus):
	outputs = []
	for key in partlocation.keys():
		values = partlocation[key]
		for value in values:
			if value[0] != -1 and value[1] != -1:
				for k in part_id_dict.keys():
					if key == part_id_dict[k]:
						query = "SELECT part_name FROM brick WHERE part_id = %s;" % (k)
						part_name = SQL(query)[0][0]
						outputs.append([part_name,key])
	try:
		route = find_route(goal, outputs)
	except IndexError:
		outputFail(rfcstatus)	#this part is doubtfull... if 1 TU is not working, does not mean the others are?
		sys.exit()
	return route

	#Creating the SQL query for the Database
def BiobrickAndSettings(BioBricks,part_results,best_quality,part_status):
	query_settings = []
	#All possible FGM combinations are made
	BioBricksList = [list(itertools.chain(*x)) for x in itertools.permutations(BioBricks, len(BioBricks))]
	if len(part_results) > 0: #If part_results are checked then this query is made same for the other two IF statements
			query_settings.append("part_results IN ('"+"' , '".join(part_results)+"')")
	if len(best_quality) > 0:
		query_settings.append("best_quality IN ('"+"' , '".join(best_quality)+"')")
	if len(part_status) > 0:
		query_settings.append("part_status IN ('"+"' , '".join(part_status)+"')")
	#All the settings are joined with the AND SQL statement
	query_settings = ' AND ' +' AND '.join(query_settings)
	return BioBricksList,query_settings

def input(): #Obtaining the users input
	#Variables are created
	BioBricks = ''
	part_results = ''
	best_quality = ''
	part_status = ''
	#Looping through the system arguments
	for i in enumerate(sys.argv):
		#If -brick is present then the next element contains the bricks
		if '-brick' in i:
			#Every FGM is separated by '#'
			BioBricks = sys.argv[i[0]+1].split('#')
			#Every part of every FGM is separated by '-'
			for BioBrick in enumerate(BioBricks):
				Biosplit = BioBrick[1].split('-')
				BioBricks[BioBrick[0]] = Biosplit		
		# Obtaining the arguments for part results/quality/status		
		if '-part_results' in i:
			part_results = sys.argv[i[0]+1].replace('_',' ').split('#')
		if '-best_quality' in i:
			best_quality = sys.argv[i[0]+1].replace('_',' ').split('#')
		if '-part_status' in i:
			part_status = sys.argv[i[0]+1].replace('_',' ').split('#')
	#All the bricks and settings are send back to the main module
	return BioBricks,part_results,best_quality,part_status

def outputFailMessage(text):
	print text
	
def outputFail(rfcstatus):
	if rfcstatus[0] == False:
		rfclist = list(set(rfcstatus[1:]))
		for brick in rfclist:
			print brick,"was incompatible with RFC[10]"
	if rfcstatus[0] == False:
		print "\nIncompatibility can be a reason that The Constructor\ndid not manage to find a suggestion for you.\n"
		print "Another reason can be the following:\n"
	print 'With the used settings the program did not\nmanage to create a construct, Try using more\nflexible parameters.',
	print 'One of the first possibilities\nis to set more options on by for example check\nWorks and None for Part Results.',
	print 'As a large range\nof parts do not have any qualifications.'
	
def outputSuccess(BioBricks,BioBricksInput,routeoutput,count):
	#print "Cloning steps:",len(routeoutput)
	print "The Constructor Result",count,":"
	header = ['Part Name', 'Part Status', 'Part Results', 'Part URL','Part Quality','Part Maker']
	
	print '<table border = "0">'
	print "<tr>"
	for head in header:
		print "<td>"+head+"</td>"
	print "</tr>"
	
	for b in routeoutput:
		print "<tr>"
		brick = b[0]
		query = "SELECT part_name, part_status, part_results, part_url, part_author, best_quality  FROM brick WHERE part_name = '%s' " % (brick)
		value = SQL(query)
		########
		#Retrieve column names
		#query = "SELECT column_name FROM information_schema.columns WHERE table_name = 'brick'"
		#print SQL(query)
		########
		part_author = value[0][4]
		part_quality = value[0][5]
		if len(part_author) > 20:
			part_author = part_author[:20]+"..."

		
		print "<td>"+value[0][0]+"</td>"
		print "<td>"+value[0][1]+"</td>"
		print "<td>"+value[0][2]+"</td>"
		print '<td><a href="%s">%s</a></td>' % (value[0][3],value[0][0])
		print "<td>"+part_quality+"</td>"
		print "<td>"+part_author+"</td>"
		print "</tr>"
	print "</table>"


def timeprint(routes,start,finish):
	timespend = finish-start
	if timespend.seconds < 60: #Less then a minute
		print "Finished within", timespend.seconds, "seconds and analysed",len(routes),"routes"
	else:
		print "Finished within", timespend.seconds/60, "minutes and",timespend.seconds%60,"seconds after analysing",len(routes),"routes"

def rfccheck(BioBricksInput):
	status = []
	status.append(True)
	for brick in BioBricksInput:
		for b in brick:
			query = 'SELECT part_name, rfc FROM brick WHERE part_name = %s AND rfc REGEXP "RFC10\\\+|RFC23\\\+|RFC25\\\+";' % ('"'+b+'"')
 			value = SQL(query)
			if len(value) == 0:
				status[0] = False
				status.append(b)
	return status


def main():
	start = datetime.now()
	#Checks if it wasnt already done
	#InputQuery = ' '.join(sys.argv[1:])
	#inDatabase = SQL("SELECT result,date FROM queries WHERE query = '%s' " % (InputQuery))
	#date = inDatabase[0][1]
	#result = inDatabase[0][0]
	#if inDatabase and date.year >= 2012 and date.month >= 6 and date.day >= 14:
	#	print "The system detected that the analysis was performed earlier and this is the result"
	#	print result
	#	sys.exit()
	
	#Obtaining the users input
	BioBricksInput,part_results,best_quality,part_status = input()

	#Check if the bricks are conform RFC standard 10,23,25
	rfcstatus = rfccheck(BioBricksInput)
	#Creating the query containing the right arguments for the SQL Database
	BioBricks,query_settings = BiobrickAndSettings(BioBricksInput,part_results,best_quality,part_status)
	#Variables are created
	count = 0
	subpartlists,part_id_dict = [], {}
	routes = []
	#Now we are going through every possibility of the FGM combinations. 
	#You know, When a user gives to FGMs, FGM1 and FGM2 then they can co-live as FGM1-FGM2 or FGM2-FGM1
	for BioBrick in BioBricks:
		#Thus BioBrick contains FGM1-FGM2 or FGM2-FGM1 if a user gives two FGMs (with more, more possibilities are made)
		count = count + 1
		#The query maker is the hard of the program for data retrieval from SQL
		subpartlists,part_id_dict,route = Querymaker(BioBrick,count,subpartlists,part_id_dict,query_settings,rfcstatus)
		routes.append(route)
	shortestroute = ''
	partcheck = []

#	for route in routes:
#		#print route
#		if len(shortestroute) == 0:
#			shortestroute = route
#		if len(route) < len(shortestroute):
#			shortestroute = route
#			
#	BioBricksInput = list(itertools.chain.from_iterable(BioBricksInput))
#	outputSuccess(BioBricks,BioBricksInput,shortestroute)
#	finish = datetime.now()
#	timeprint(routes,start,finish)
	
	BioBricksInput = list(itertools.chain.from_iterable(BioBricksInput))
	

	#Removes duplicates from list of lists
	routes = list(routes for routes,_ in itertools.groupby(routes))
	routes  = sorted(routes, lambda x,y: 1 if len(x)>len(y) else -1 if len(x)<len(y) else 0)
	
	count = 0
	
	for route in routes[:3]:
		count = count + 1
		outputSuccess(BioBricks,BioBricksInput,route,count)

	finish = datetime.now()
	timeprint(routes,start,finish)	

def SQLu(query):
	db = MySQLdb.connect('127.0.0.1', 'username', 'password', 'databasename');	
	cursor = db.cursor()
	cursor.execute(query)
	db.commit()
		
#query = "DELETE FROM brick WHERE part_name = 'BBa_E0022';"
#SQL(query)
#query = "UPDATE brick set part_results = 'NoneX' where part_results is NULL;" 
#print query
#SQL(query)
#query = "UPDATE brick SET part_results = 'None' WHERE part_name = 'BBa_R0073';"
#print query
#SQLu(query)
#print '[DEBUGGING]'
#query = "Select COUNT(part_name) from brick WHERE rfc REGEXP 'RFC10\\\+|RFC23\\\+|RFC25\\\+';"
#print SQL(query)
#query = "Select COUNT(part_name) from brick;"
#print SQL(query)
#query = """UPDATE brick set part_results = "None" WHERE part_results = 'None'"""
#SQLu(query)
#query = 'SELECT * FROM brick where part_name = \'BBa_E0022\' and part_results = "None";'
#print SQL(query)
#query = "SELECT part_name, part_results FROM brick where part_name = 'BBa_E0022';"
#print SQL(query)
#query = "SELECT part_name, part_results FROM brick where part_name = 'BBa_R0073';"
#print SQL(query)
#query = "SELECT COUNT(part_name) FROM brick"
#print SQL(query)
# query = "SELECT part_name, part_results, best_quality, part_status FROM brick where part_name = 'BBa_K176028';"
# print SQL(query)
# query = "SELECT part_name, part_results, best_quality, part_status FROM brick where part_name = 'BBa_I0460';"
# print SQL(query)
# query = "SELECT part_name, part_results, best_quality, part_status FROM brick where part_name = 'BBa_K182102';"
# print SQL(query)
# query = "SELECT part_name, part_results, best_quality, part_status FROM brick where part_name = 'BBa_C0061';"
# print SQL(query)
# query = "SELECT part_name, part_results, best_quality, part_status FROM brick where part_name = 'BBa_I13033';"
# print SQL(query)
#query = "SELECT part_id, part_name, part_results, best_quality, part_status FROM brick where part_name = 'BBa_K283026';"
#print SQL(query)
#query = "SELECT part_id, part_name, part_results, best_quality, part_status FROM brick where part_name = 'BBa_X';"
#print SQL(query)
#query = "SELECT * FROM superpart WHERE brick_part_id = 0;"
#print SQL(query)
#query = "INSERT INTO brick VALUES (0,'BBa_X','X','New part (does not exist in database yet)','Other','Available','Works','X',NULL,'http://2012.igem.org/Team:Wageningen_UR','2012-05-25','Wageningen UR 2012','Confirmed','thisdoesntexist',NULL)"
#print SQLu(query)
#query = "INSERT INTO superpart VALUES (0,0,0)"
#print SQLu(query)

#query = "SELECT part_name,rfc FROM brick WHERE part_name = 'BBa_P0353' AND rfc = 'RFC10+,RFC12+,RFC21+,RFC23+,RFC25+';"
#print SQL(query)
#query = "SELECT part_name,rfc FROM brick WHERE part_name = 'BBa_P0353' AND rfc LIKE '%RFC10+%';"
#print SQL(query)

main()

