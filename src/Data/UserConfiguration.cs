using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using WebApplication1.User.Models;

namespace WebApplication1.Data;

public class UserConfiguration : IEntityTypeConfiguration<UserModel>
{
    public void Configure(EntityTypeBuilder<UserModel> builder)
    {
        builder.HasKey(u => u.Id);

        builder.Property(u => u.Id)
            .ValueGeneratedOnAdd();

        builder.Property(u => u.Email)
            .IsRequired();

        builder.Property(u => u.PasswordHashed)
            .IsRequired();

        builder.Property(u => u.Email)
            .IsRequired();

        builder.HasIndex(u => new { u.Email })
            .IsUnique();
    }
}