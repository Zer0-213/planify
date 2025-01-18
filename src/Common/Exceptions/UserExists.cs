namespace WebApplication1.Common.Exceptions;

public class UserAlreadyExistsException(string username)
    : Exception($"A user with the username '{username}' already exists.");