using WebApplication1.Authentication.Models;
using WebApplication1.User;

namespace WebApplication1.Authentication.Repositories;

public interface IAuthenticationRepository
{
    SessionModel? Authenticate(string email, string password);
    SessionModel CreateAccount(UserModel registerDto);
}