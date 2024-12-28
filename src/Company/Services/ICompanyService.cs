using WebApplication1.Company.DTOs;

namespace WebApplication1.Company.Services;

public interface ICompanyService
{
    bool CreateCompany(CreateCompanyDto createCompany);
}