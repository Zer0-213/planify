using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using WebApplication1.Permission;

namespace WebApplication1.Data;

public class PermissionConfiguration : IEntityTypeConfiguration<PermissionModel>
{
    public void Configure(EntityTypeBuilder<PermissionModel> builder)
    {
        builder.HasKey(p => p.Id);

        builder.Property(p => p.Id)
            .ValueGeneratedOnAdd();

        builder.Property(p => p.Name)
            .IsRequired();

        builder.Property(p => p.Description);

        builder.HasData(
            new PermissionModel
            {
                Id = 1, Name = "Admin",
                Description =
                    "Admin users can manage all aspects of the system, including user management, role assignments, system settings, and access to sensitive data."
            },
            new PermissionModel
            {
                Id = 2, Name = "Manager",
                Description =
                    "Managers can view and modify schedules, manage teams, and perform certain administrative tasks like approving time-off requests, but they do not have access to system-wide settings or user management"
            },
            new PermissionModel
            {
                Id = 3, Name = "Employee",
                Description =
                    "Employees can view their schedules, submit time-off requests, and perform other tasks related to their work schedule, but they cannot make changes to the system or other users' data."
            }
        );
    }
}