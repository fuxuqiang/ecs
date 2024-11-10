#!/bin/bash

echo "Enter your username:"

read USERNAME

PSQL="psql -U freecodecamp -d number_guess --tuples-only -c"

# query playing record
read GAMES_PLAYED BAR BEST_GAME <<< $($PSQL "SELECT COUNT(*), MIN(times) FROM record WHERE username='$USERNAME'")

if [[ $GAMES_PLAYED == 0 ]]
then
    echo "Welcome, $USERNAME! It looks like this is your first time here."
else
    echo "Welcome back, $USERNAME! You have played $GAMES_PLAYED games, and your best game took $BEST_GAME guesses."
fi

# randomly generate a number
NUMBER=$((1 + $RANDOM % 1000))
# times of plays
TIMES=0

echo Guess the secret number between 1 and 1000:

VERIFY_NUMBER() {
    read INPUT_NUMBER
    if [[ $INPUT_NUMBER =~ ^[0-9]+$ ]]
    then
        ((TIMES++))
        if [[ $INPUT_NUMBER == $NUMBER ]]
        then
            # win the game and insert record
            echo "You guessed it in $TIMES tries. The secret number was $NUMBER. Nice job!"
            RECORD_RESULT=$($PSQL "INSERT INTO record(username, times) VALUES('$USERNAME', $TIMES)")
        else
            if [[ $INPUT_NUMBER > $NUMBER ]]
            then
                echo "It's higher than that, guess again:"
            else
                echo "It's lower than that, guess again:"
            fi
            VERIFY_NUMBER
        fi
    else
        echo That is not an integer, guess again:
    fi
}

VERIFY_NUMBER