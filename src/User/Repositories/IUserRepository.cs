namespace WebApplication1.User.Repositories;

public interface IUserRepository
{
    UserModel QueryUserById(int id);
}