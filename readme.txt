Erweiterte Umfrage 1.5 für IlchClan 1.1 J oder höher:
"""""""""""""""""""""""
Beschreibung:
-------------
Man kann bei Umfragen angeben, welche Usergruppen und Teams/Gruppen an der Umfrage teilnehmen können,
ab welchem Recht das Ergebnis sichtbar ist und dass die Umfrage zu einem bestimmten Zeitpunkt ausläuft oder maximale Anzahl an Abstimmungen.
Außerdem ist es möglich mehrere Antworten zu zulassen.

Box: Hier wird eine Umfrage angezeigt, bei der man abstimmen kann oder eine zufällige von der man das Ergebnis sehen kann.
Content: Bei Umfragen, die nicht für die eigene Usergruppe/Teams/Gruppen sind und deren Ergebnis man sehen darf, wird das Ergebnis angezeigt.
Adminmenü: Erweitert um die Auswahl für welche Usergruppen/Teams/Gruppen ein Vote ist und für welche Usergruppen das Ergebnis sichtbar ist.

Changelog:
----------
°Version 1.5:
	- Fehler beim Editieren von Umfragen bei denen man Antworten hinzufügt behoben
	- Option für maximale Abstimmanzahl hinzugefügt

°Version 1.4:
	- Mehrere Antwortmöglichkeiten 

°Version 1.3c:
	- Bugfix: Es konnten auch andere Membergruppen abstimmen als angegben :S
	- Bugfix keine Division durch 0 mehr

°Version 1.3b:
	- Bugfix in include/admin/vote.php, Fehler erzeugte Fehlermeldung,
	  wenn man kein Datum eingefügt hatte beim ändern/einfügen einer neuen Umfrage

°Version 1.3:
	- Ablaufdatum hinzugefügt, wird nach dem Ablaufdatum ein Stimme abgegeben
	  wird diese nicht mehr gezählt und die Umfrage geschlossen

°Version 1.2:
	- Funktion für Sichtbarkeit des Ergebnisses hinzugefügt
	- Box überarbeitet -> zufälliges Ergebnis wird angezeigt

°Version 1.1:
	- Funktion für Teams/Gruppen hinzugefügt
	- Codeoptimierung

°Version 1.0:
	- erstes Release mit Funktion für Usergruppen

Entwickelt
----------
° von "Mairu"
° auf Basis von IlchClan 1.1

Installation:
-------------
° alle Dateien im Ordner upload, in ihrer Ordnerstrucktur hochladen
  !! Vorsicht: Überschreibt die: !!
	| include/boxes/vote.php
	| include/contents/vote.php 
	| include/admin/vote.php

° install.php ausführen (auch bei Updates), indem man es im Webbrowser aufruft ( z.B. http://meine.seite.de/install.php ) und danach wieder löschen

Bekannte Einschränkungen / Fehler:
----------------------------------
° Bei der Anzeige der Umfragen stimmt die Blätterfunktion nicht immer, also es kann vorkommen,
  dass auf der folgenden Seite Umfragen wiederholt werden.
° Bei mehreren Antwortmöglichkeiten zählt jede Antwort als eigene Stimme

Haftungsausschluss:
-------------------
Ich übernehme keine Haftung für Schäden, die durch dieses Skript entstehen.
Benutzung ausschließlich AUF EIGENE GEFAHR.

Fehler und Fragen, die dieses Modul betreffen bitte im Forum melden ( http://www.ilch.de/forum-showposts-15531.html )
