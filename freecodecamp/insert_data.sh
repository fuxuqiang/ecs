#! /bin/bash

if [[ $1 == "test" ]]
then
  PSQL="psql --username=postgres --dbname=worldcuptest -t --no-align -c"
else
  PSQL="psql --username=freecodecamp --dbname=worldcup -t --no-align -c"
fi

# Do not change code above this line. Use the PSQL variable above to query your database.
echo $($PSQL "TRUNCATE teams, games")

# insert team
INSERT_TEAM() {
  INSERT_TEAM_RESULT=$($PSQL "INSERT INTO teams(name) VALUES('$1')")
  echo $(GET_TEAM_ID "$1")
}

GET_TEAM_ID() {
  echo $($PSQL "SELECT team_id FROM teams WHERE name='$1'")
}

cat games.csv | while IFS="," read YEAR ROUND WINNER OPPONENT WINNER_GOALS OPPONENT_GOALS
do
  if [[ $YEAR != "year" ]]
  then
    # get team_id
    WINNER_ID=$(GET_TEAM_ID "$WINNER")
    OPPONENT_ID=$(GET_TEAM_ID "$OPPONENT")
    # if not found
    if [[ -z $WINNER_ID ]]
    then
      # get new team_id
      WINNER_ID=$(INSERT_TEAM "$WINNER")
    fi
    # if not found
    if [[ -z $OPPONENT_ID ]]
    then
      # get new team_id
      OPPONENT_ID=$(INSERT_TEAM "$OPPONENT")
    fi

    # insert into games
    INSERT_GAMES_RESULT=$($PSQL "INSERT INTO games(year,round,winner_id,opponent_id,winner_goals,opponent_goals) VALUES($YEAR,'$ROUND',$WINNER_ID,$OPPONENT_ID,$WINNER_GOALS,$OPPONENT_GOALS)")
    if [[ $INSERT_GAMES_RESULT == "INSERT 0 1" ]]
    then
      echo Inserted into games, $YEAR : $ROUND
    fi
  fi
done
