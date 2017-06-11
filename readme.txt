Erweiterte Umfrage 1.5 f�r IlchClan 1.1 J oder h�her:
"""""""""""""""""""""""
Beschreibung:
-------------
Man kann bei Umfragen angeben, welche Usergruppen und Teams/Gruppen an der Umfrage teilnehmen k�nnen,
ab welchem Recht das Ergebnis sichtbar ist und dass die Umfrage zu einem bestimmten Zeitpunkt ausl�uft oder maximale Anzahl an Abstimmungen.
Au�erdem ist es m�glich mehrere Antworten zu zulassen.

Box: Hier wird eine Umfrage angezeigt, bei der man abstimmen kann oder eine zuf�llige von der man das Ergebnis sehen kann.
Content: Bei Umfragen, die nicht f�r die eigene Usergruppe/Teams/Gruppen sind und deren Ergebnis man sehen darf, wird das Ergebnis angezeigt.
Adminmen�: Erweitert um die Auswahl f�r welche Usergruppen/Teams/Gruppen ein Vote ist und f�r welche Usergruppen das Ergebnis sichtbar ist.

Changelog:
----------
�Version 1.5:
	- Fehler beim Editieren von Umfragen bei denen man Antworten hinzuf�gt behoben
	- Option f�r maximale Abstimmanzahl hinzugef�gt

�Version 1.4:
	- Mehrere Antwortm�glichkeiten 

�Version 1.3c:
	- Bugfix: Es konnten auch andere Membergruppen abstimmen als angegben :S
	- Bugfix keine Division durch 0 mehr

�Version 1.3b:
	- Bugfix in include/admin/vote.php, Fehler erzeugte Fehlermeldung,
	  wenn man kein Datum eingef�gt hatte beim �ndern/einf�gen einer neuen Umfrage

�Version 1.3:
	- Ablaufdatum hinzugef�gt, wird nach dem Ablaufdatum ein Stimme abgegeben
	  wird diese nicht mehr gez�hlt und die Umfrage geschlossen

�Version 1.2:
	- Funktion f�r Sichtbarkeit des Ergebnisses hinzugef�gt
	- Box �berarbeitet -> zuf�lliges Ergebnis wird angezeigt

�Version 1.1:
	- Funktion f�r Teams/Gruppen hinzugef�gt
	- Codeoptimierung

�Version 1.0:
	- erstes Release mit Funktion f�r Usergruppen

Entwickelt
----------
� von "Mairu"
� auf Basis von IlchClan 1.1

Installation:
-------------
� alle Dateien im Ordner upload, in ihrer Ordnerstrucktur hochladen
  !! Vorsicht: �berschreibt die: !!
	| include/boxes/vote.php
	| include/contents/vote.php 
	| include/admin/vote.php

� install.php ausf�hren (auch bei Updates), indem man es im Webbrowser aufruft ( z.B. http://meine.seite.de/install.php ) und danach wieder l�schen

Bekannte Einschr�nkungen / Fehler:
----------------------------------
� Bei der Anzeige der Umfragen stimmt die Bl�tterfunktion nicht immer, also es kann vorkommen,
  dass auf der folgenden Seite Umfragen wiederholt werden.
� Bei mehreren Antwortm�glichkeiten z�hlt jede Antwort als eigene Stimme

Haftungsausschluss:
-------------------
Ich �bernehme keine Haftung f�r Sch�den, die durch dieses Skript entstehen.
Benutzung ausschlie�lich AUF EIGENE GEFAHR.

Fehler und Fragen, die dieses Modul betreffen bitte im Forum melden ( http://www.ilch.de/forum-showposts-15531.html )
