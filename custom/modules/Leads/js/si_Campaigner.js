document.addEventListener("DOMContentLoaded", function () {
  getCompanyData();
  var approveButton = document.createElement("input");
  approveButton.title = "Approve";
  approveButton.accessKey = "a";
  approveButton.className = "button primary";
  approveButton.type = "submit";
  approveButton.name = "button";
  approveButton.value = "Approve";
  approveButton.id = "APPROVE";

  var buttonsElement =
    document.querySelector("ul.nav.nav-tabs") ||
    document.querySelector(".buttons");
  buttonsElement.appendChild(approveButton);
});

function getCompanyData() {
  var leadId = document.querySelector('input[name="record"]').value;
  const request = new Request(
    "index.php?module=si_Campaigner&action=getCompanyData&to_pdf=1&leadId=" +
      leadId,
    {
      method: "GET",
    }
  );

  fetch(request)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error("Request failed");
      }
    })
    .then((data) => {
      if (!data) return;
      if (data.si_company_linkedin_profile_c) {
        var companyProfileElement = document.getElementById(
          "si_company_linkedin_profile_c"
        );

        if (companyProfileElement.tagName === "INPUT") {
          // In EditView, update the input field value
          companyProfileElement.value = data.si_company_linkedin_profile_c;
        } else {
          // In DetailView, update the innerHTML
          companyProfileElement.innerHTML =
            "<a target='_blank' href='" +
            data.si_company_linkedin_profile_c +
            "'>" +
            data.si_company_linkedin_profile_c +
            "</a>";
        }
      }

      if (data && data.si_company_linkedin_bio_c) {
        var companyBioElement = document.getElementById(
          "si_company_linkedin_bio_c"
        );
        companyBioElement.innerHTML = data.si_company_linkedin_bio_c;
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
}
