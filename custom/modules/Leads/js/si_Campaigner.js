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

  var buttonsElement = document.querySelector(".buttons");
  buttonsElement.appendChild(approveButton);
});

function getCompanyData() {
  var id = document.querySelector('input[name="record"]').value;
  const request = new Request(
    "index.php?module=si_Campaigner&action=leadscontroller&to_pdf=1&sugar_body_only=true",
    {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: new URLSearchParams({
        method: "getCompanyData",
        id: id,
      }),
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
      if (data && data.si_company_linkedin_profile_c) {
        document.getElementById("si_company_linkedin_profile_c").value =
          data.si_company_linkedin_profile_c;
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
}
