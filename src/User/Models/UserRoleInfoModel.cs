using WebApplication1.Roles;

namespace WebApplication1.User.Models;

public class UserRoleInfoModel
{
    public int UserId { get; set; }
    public string FirstName { get; set; } = string.Empty;
    public string LastName { get; set; } = string.Empty;
    public string Email { get; set; } = string.Empty;
    public string PhoneNumber { get; set; } = string.Empty;
    public List<RolesModel> Roles { get; set; } = []; 
}