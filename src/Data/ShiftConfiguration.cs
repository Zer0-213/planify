using Microsoft.EntityFrameworkCore;
using Microsoft.EntityFrameworkCore.Metadata.Builders;
using WebApplication1.Shifts.Models;

namespace WebApplication1.Data;

public class ShiftConfiguration: IEntityTypeConfiguration<ShiftModel>
{
    public void Configure(EntityTypeBuilder<ShiftModel> builder)
    {
        builder.HasKey(x => x.Id);
        builder.Property(x => x.Id).HasColumnName("id");
        builder.Property(x => x.Id).ValueGeneratedOnAdd();
        builder.Property(x => x.StartTime).IsRequired();
        builder.Property(x => x.EndTime).IsRequired();
        
        builder.HasOne(x => x.User)
            .WithMany()
            .HasForeignKey(x => x.UserId);
        
        builder.HasOne(x => x.Company).
            WithMany().
            HasForeignKey(x => x.CompanyId);
    }
}