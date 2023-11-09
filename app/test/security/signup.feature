Feature: Create user
  In order to open an online shop
  As a visitor
  I need to be able to create an account

  Rule: An account must have a uniq email

    Scenario: creating a new user
      Given I have the following email "test@cogelec.fr"
      When I try to create an account
      Then I get the message "Your account has been created, check your mailing test@cogelec.fr"

    Scenario: trying to create another user with the same email
      Given I have the following email "test@cogelec.fr"
      When I try to create an account
      Then I get the message "The email test@cogelec.fr is already registered"