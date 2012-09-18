<?php
if(!isset($onserver)) { die('Restricted access'); }
?>
<h1>About The Constructor</h1>

<h2>History of The Constructor</h2>

<p>The <a href="http://partsregistry.org/Main_Page" title="Registry of Standard Biological Parts">Registry of Standard Biological Parts</a> is rapidly expanding. Since 2003 more than 20.000 BioBrick parts were added by iGEM teams and labs from all over the world, making the life of a synthetic biologist easier and easier with each passing year.</p>

<p>The increase of BioBrick parts - especially of the composite parts - could facilitate fast cloning of the circuit of interest. Unfortunately, optimizing your cloning strategy by searching the registry for useful composite parts is laborious and time consuming. Even though the search is time consuming it could be worth doing, since reducing the amount of cloning steps will significantly decrease the amount of time needed for the actual lab work thereby giving you more time for whatever else needs to be done.</p>

<p>That's why <a href="http://2011.igem.org/Team:Wageningen_UR" title="Wageningen UR iGEM 2011">Wageningen UR iGEM 2011 team</a> set out to make a program, that calculates the most efficient cloning strategy for your circuit of interest. The program developed is called &quot;The Constructor&quot; of which the general user interface can be seen on the website of <a href="http://2011.igem.org/Team:Wageningen_UR/Softwaretool" title="Wageningen UR iGEM 2011">Wageningen UR's iGEM 2011 team</a>.</p>

<p>This year, the <a href="http://2012.igem.org/Team:Wageningen_UR" title="Wageningen UR iGEM 2012">Wageningen UR iGEM 2012 team</a> took it a step further and created the <a href="/" title="The Contructor">web-based version of The Constructor</a>.</p>

<h2>Mode of Action</h2>

<p>The constructor works as follows: for example, you want to create the following genetic circuit of 1-2-3-4-5-6. A number in this circuit represents what we call a Transcription Unit (TU). We defined a TU as followed: a TU starts with a promotor and ends with a terminator, the parts in between can vary. For example, the following subparts (R0062-B0034-E1010-B0010-B0012) combined will form a Transcription Unit, because it starts with R0063 - which is the lux pL promoter and it ends with B0010-B0012- which is the rrnBT1-T7TE terminator. However, R0062-B0034-C0062-B0034-C0061-B0010-B0012 would also classify as a TU, because it still starts with a promoter and ends with a terminator.</p>

<p>The hypothetical circuit consists of 6 of these TU’s. Because each TU can function on its own due to the promotor and terminator, it doesn’t matter where it is in your construct. So you can just scramble the sequence of the TU’s of the hypothetical construct, to for example 135264 or 125643. This won’t have any adverse effects on the molecular workings, due to the promotors and terminators.</p>

<p>For this hypothetical circuit there are 720 (6 faculty) different combinations of arranging the functional modules. The rearranging of the sequence of the functional modules is critical for optimizing the cloning strategy due to the following: lets say we use the previous mentioned functional modules R0062-B0034-E1010-B0010-B0012 (1) and R0062- B0034-C0062-B0034-C0061-B0010-B0012 (2). With these two modules two combination can be made namely 1-2 and 2-1, as seen below.</p>

<p>(1-2) R0062-B0034-E1010-B0010-B0012-R0062-B0034-C0062-B0034-C0061-B0010-B0012</p>

<p>(2-1) R0062-B0034-C0062-B0034-C0061-B0010-B0012- R0062-B0034-E1010-B0010-B0012</p>

<p>With these two combinations we search the registry for composite parts which could be used to build the circuit with. Let’s say you will get the following composite parts for the two constructs:</p>
<p>
(1-2)<br />
R0062-B0034-E1010<br />
B0010-B0012<br />
R0062- B0034-C0062<br />
B0034-C0061-B0010-B0012
</p>

<p>
(2-1)<br />
R0062-B0034-E1010<br />
B0010-B0012<br />
R0062- B0034-C0062<br />
B0034-C0061-B0010-B0012<br />
R0062-B0034-C0062-B0034-C0061-B0010-B0012- R0062-B0034-E1010
<p>

<p>As you can see the majority of composite parts found could be used for both circuits, however for circuit 2-1 you find an additional composite part namely.</p>

<p>R0062-B0034-C0062-B0034-C0061-B0010-B0012-R0062-B0034-E1010</p>

<p>This composite part combined with the B0010-B0012 part will yield the desired circuit. Thus by rearranging the sequence of these functional modules 1 and 2 you effectively reduce the amount of cloning as seen at 1-2 arrangement from 3 cloning steps to 1 cloning step in the 2-1 arrangement.</p>

<p>Furthermore, the program implements a few filters for part quality, part availability and part experience. With these filters only parts containing the criteria which you selected are taken from the database and subsequently used in optimizing your cloning strategy.</p>

<h2>Contributors to The Constructor</h2>

<h3>Matthijn Hesselman</h3>
<p>The brain behind the initial version of The Constructor. Matthijn developped the algorithm and made the initial offline version of the tool.</p>

<h3>Jasper Koehorst</h3>
<p>Rewrote The Contructor to use a MySQL database and made it possible to run The Constructor on a server.  Extended the algorithm.</p>

<h3>Thijs Slijkhuis</h3>
<p>Created the Graphical User Interface and programmed the interface between The Constructor and the GUI.</p>

<h3>Floor Hugenholtz</h3>
<p>Supervised the project from the start.</p>

<h3>Mark van Passel</h3>
<p>Supervised the project from the start.</p>