# farm-game

Blenheim Chalcot Interview Test

The objective of the test is to validate your coding standards / quality of code, some of the things to look out for is as follows
Block alignment: Every block of code and curly braces must be aligned.
Short Functions: All functions and methods must limit themselves to a single page and must not be lengthy.
Line length and Indentation: It is a standard recommendation to not exceed more than 75-85 characters per line of code. One must not use tabs for indentation instead use 4 spaces as it is the standard indenting method in most of the programming languages.
Naming Variables:
Use of lower case letters to name the variables.
Use of ‘_’ to separate the words in a variable.
Commenting : Use of standard C and C++ commenting style i.e., (//) – for single line and (/* */) – for multi-line, 
Your peer programmers have to understand the code you produce.


Here are the few synopsis which will help you to design the project.  
 
For each animal / person please indicate on what round they have been fed
You must determine if any have died after each feed
Indicate if any have died (you should not feed the dead ones in subsequent rounds) (suggest change background of header of dead animal / person to red) remember who has to be fed is determined randomly by the system, so you need to ensure that your random logic does not include dead entities e.g. initial logic runs on an array {1,2,3,4,5,6,7}, in the course of the game the 4 dies (bunny 1) your subsequent array for randomization would be {1,2,3,5,6,7}
If the farmer dies then the game should be over (disable click)
If 50 rounds are completed then the game is over (disable click)
Include unit tests where appropriate
The objective is not to “win”, remember who has to be fed is determined randomly by the system and you have not control on it
Please read the problem statement thoroughly to understand the requirement
Game summary should indicate if you have won after the game has ended in any scenario (4 or 5) (based on the winning condition described in the logic)


# Farm Game
**Objective**

Create a PHP application that performs the following game.

A web page must be produced as the interface to play the game. Styling is neither expected nor necessary.

The application must run in a browser.

**The game**

You have a farm with:

- 1 farmer: needs to be fed at least once every 15 turns.
- 2 cows: each cow needs to be fed at least once every 10 turns.
- 4 bunnies: each bunny needs to be fed at least once every 8 turns.

If any of the animals or the farmer are not fed on time, they die. If the farmer dies, all animals die and the game is over.

The game ends after 50 turns. If the farmer and at least one cow and one bunny are still alive at that point, you win.

**How to play**

- There’s a button to start a new game.
- There's a single button to feed the farmer and the animals. 
Every time you click on that button, the system randomly chooses whom to feed. 
Every click on this button is a turn. The maximum number of turns is 50.
Don't focus on winning or losing, but on building the game as described.

**What we expect**
- Create a new branch for you to use and push your code.
- Add all commits when possible, so we can see your work process.
- Highlight your PHP skills.
- But also add unit tests wherever appropriate.

Good luck, and thanks for your time and interest :+1:

