# features/User.feature
Feature: User
  In order to battle projects
  As an API user
  I need to be able to create user

  Background:
    # Given the user "test2" exists

  Scenario: Create a User
    """
    {
      "test22",
      "test22@example.com",
      "test22Pass",
      "Male",
    }
    """
    When I request "/users/newUser"
    Then the response status code should be ok
    #And the "Location" header should be "/user/newUser/test22"
    #And the "usrName" property should equal "test22"