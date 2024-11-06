#!/bin/bash

PSQL="psql -U fu -d salon --tuples-only -c"

echo -e "\n~~~~~ MY SALON ~~~~~\n"
echo -e "Welcome to My Salon, how can I help you?\n"

MAIN_MENU() {
    if [[ $1 ]]
    then
        echo -e "\n$1"
    fi

    echo "$($PSQL "SELECT service_id,name FROM services")" | while read SERVICE_ID BAR NAME
    do
        echo "$SERVICE_ID) $NAME"
    done

    PIKE_SERVICE
}

PIKE_SERVICE() {
    read SERVICE_ID_SELECTED
    SERVICE_EXISTENCE=$($PSQL "SELECT * FROM services WHERE service_id=$SERVICE_ID_SELECTED")
    if [[ -z $SERVICE_EXISTENCE ]]
    then
        MAIN_MENU "I could not find that service. What would you like today?"
    else
        echo -e "\nWhat's your phone number?"
        read CUSTOMER_PHONE
        CUSTOMER_NAME=$($PSQL "SELECT name FROM customers WHERE phone = '$CUSTOMER_PHONE'")

        # if customer doesn't exist
        if [[ -z $CUSTOMER_NAME ]]
        then
          # get new customer name
          echo -e "\nI don't have a record for that phone number, what's your name?"
          read CUSTOMER_NAME

          # insert new customer
          INSERT_CUSTOMER_RESULT=$($PSQL "INSERT INTO customers(name, phone) VALUES('$CUSTOMER_NAME', '$CUSTOMER_PHONE')") 
        fi

        # get customer_id
        CUSTOMER_ID=$($PSQL "SELECT customer_id FROM customers WHERE phone='$CUSTOMER_PHONE'")

        echo -e "\nWhat time would you like your cut, $CUSTOMER_NAME?"
        read SERVICE_TIME
        INSERT_APPOINTMENT_RESULT=$($PSQL "INSERT INTO appointments(customer_id, service_id, time) VALUES($CUSTOMER_ID, $SERVICE_ID_SELECTED, '$SERVICE_TIME')")
        echo -e "\nI have put you down for a cut at $SERVICE_TIME, $CUSTOMER_NAME."
    fi
}

MAIN_MENU