using WebApplication1.Company.DTOs;
using WebApplication1.Company.Models;
using WebApplication1.Company.Repositories;

namespace WebApplication1.Company.Services;

public class CompanyService(ICompanyRepository companyRepository) : ICompanyService
{
    public bool CreateCompany(CreateCompanyDto createCompany)
    {
        var success = companyRepository.CreateCompany(createCompany.UserId,
            new CompanyModel
            {
                Name = createCompany.CompanyName,
                PhoneNumber = createCompany.CompanyPhone,
                Type = createCompany.CompanyType
            });

        return success;
    }
}