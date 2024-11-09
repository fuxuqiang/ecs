#!/bin/bash

if [[ -z $1 ]]
then
    # if has no argument
    echo Please provide an element as an argument.
else
    PSQL="psql -U freecodecamp -d periodic_table --tuples-only -c"
    IFS=' | '
    SQL="SELECT atomic_number, symbol, name, type, atomic_mass, melting_point_celsius, boiling_point_celsius FROM properties INNER JOIN elements USING(atomic_number) INNER JOIN types USING(type_id) WHERE "

    # if argument is a number
    if [[ $1 =~ ^[0-9]+$ ]]
    then
        ENTIRE_SQL=$SQL"atomic_number = $1"
    else
        ENTIRE_SQL=$SQL"symbol = '$1' OR name = '$1'"
    fi

    ELEMENT_RESULT=$($PSQL "$ENTIRE_SQL")
    if [[ -z $ELEMENT_RESULT ]]
    then
        echo I could not find that element in the database.
    else
        read ATOMIC_NUMBER SYMBOL NAME TYPE ATOMIC_MASS MELTING_POINT BOILING_POINT <<< $ELEMENT_RESULT
        echo "The element with atomic number $ATOMIC_NUMBER is $NAME ($SYMBOL). It's a $TYPE, with a mass of $ATOMIC_MASS amu. $NAME has a melting point of $MELTING_POINT celsius and a boiling point of $BOILING_POINT celsius."
    fi
fi
