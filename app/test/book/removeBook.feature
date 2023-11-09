Feature: Book deletion
  In order to show only books on sell
  As the bookseller
  I need to be able to delete books from my shop

  Rule: An author has to be deleted if his last book is deleted

  Scenario: Deleting a book
    Given I am logged as with user id "1"
    When I try to delete the book with id "1"
    Then I get the message "Your book has been deleted"
    And The author with id "1" does not exist anymore

  Scenario: The bookseller try to delete a book from someone else
    Given I am logged as with user id "1"
    When I try to delete the book with id "2"
    Then I get the message "It's not your book!"