document.addEventListener("DOMContentLoaded", function () {
  getCompanyData();
  appendButtons();
  document
    .getElementById("sendemail")
    .addEventListener("click", function (event) {
      event.preventDefault();
      sendEmailRequest();
    });
});

function appendButtons() {
  var approveButton = document.createElement("input");
  approveButton.title = "Send Email";
  approveButton.accessKey = "a";
  approveButton.className = "button primary";
  approveButton.type = "submit";
  approveButton.name = "button";
  approveButton.value = "Send Email";
  approveButton.id = "sendemail";

  var buttonsElement =
    document.querySelector("ul.nav.nav-tabs") ||
    document.querySelector(".buttons");
  buttonsElement.appendChild(approveButton);
}

function sendEmailRequest() {
  var leadId = document.querySelector('input[name="record"]').value;

  fetch("index.php?module=Leads&action=si_sendEmail&to_pdf=1&id=" + leadId, {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  })
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error("Request failed");
      }
    })
    .then((data) => {
      // Handle the response data if needed
      console.debug("Email request successful:");
    })
    .catch((error) => {
      console.error("Error sending email request:", error);
    });
}

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
      if (data.si_company_linkedin_profile) {
        var companyProfileElement = document.getElementById(
          "si_company_linkedin_profile"
        );

        if (companyProfileElement.tagName === "INPUT") {
          // In EditView, update the input field value
          companyProfileElement.value = data.si_company_linkedin_profile;
        } else {
          // In DetailView, update the innerHTML
          companyProfileElement.innerHTML =
            "<a target='_blank' href='" +
            data.si_company_linkedin_profile +
            "'>" +
            data.si_company_linkedin_profile +
            "</a>";
        }
      }

      if (data && data.si_company_description) {
        var companyBioElement = document.getElementById(
          "si_company_description"
        );
        companyBioElement.innerHTML = data.si_company_description;
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
}
