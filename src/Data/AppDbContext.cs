using Microsoft.EntityFrameworkCore;
using WebApplication1.Authentication.Models;
using WebApplication1.Company.Models;
using WebApplication1.Permission;
using WebApplication1.Roles;
using WebApplication1.User.Models;
using WebApplication1.UserRole.Models;

namespace WebApplication1.Data;

public class AppDbContext(DbContextOptions<AppDbContext> options) : DbContext(options)
{
    public DbSet<UserModel> Users { get; set; }
    public DbSet<RolesModel> Roles { get; set; }
    public DbSet<PermissionModel> Permissions { get; set; }
    public DbSet<SessionModel> Sessions { get; set; }
    public DbSet<CompanyModel> Companies { get; set; }
    public DbSet<UserRoleModel> UserRoles { get; set; }


    protected override void OnModelCreating(ModelBuilder modelBuilder)
    {
        modelBuilder.ApplyConfiguration(new UserConfiguration());
        modelBuilder.ApplyConfiguration(new RolesConfiguration());
        modelBuilder.ApplyConfiguration(new PermissionConfiguration());
        modelBuilder.ApplyConfiguration(new SessionConfiguration());
        modelBuilder.ApplyConfiguration(new CompanyConfiguration());
        modelBuilder.ApplyConfiguration(new UserRoleConfiguration());

        base.OnModelCreating(modelBuilder);
    }
}