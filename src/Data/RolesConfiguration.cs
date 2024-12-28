using System.ComponentModel.DataAnnotations.Schema;
using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using WebApplication1.Roles;

namespace WebApplication1.Data;

[Table("roles")]
public class RolesConfiguration : IEntityTypeConfiguration<RolesModel>
{
    public void Configure(EntityTypeBuilder<RolesModel> builder)
    {
        builder.HasKey(r => r.Id);

        builder.Property(r => r.Id)
            .ValueGeneratedOnAdd();

        builder.Property(r => r.Name)
            .IsRequired();

        builder.Property(r => r.Description);

        builder.HasOne(r => r.Permission)
            .WithMany()
            .HasForeignKey(r => r.PermissionId);
    }
}