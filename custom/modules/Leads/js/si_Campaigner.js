document.addEventListener("DOMContentLoaded", function () {
  formatHref(
    "si_linkedin_profile",
    document.getElementById("si_linkedin_profile").innerText
  );
  const observer = new MutationObserver(function (mutationsList) {
    for (const mutation of mutationsList) {
      if (mutation.type === "childList" && mutation.addedNodes.length > 0) {
        for (const node of mutation.addedNodes) {
          if (node instanceof HTMLElement) {
            const textareas = node.querySelectorAll("textarea");
            textareas.forEach((textarea) => {
              applyTextareaStyling(textarea);
            });
          }
        }
      }
    }
  });

  const inlineEditFields = document.querySelectorAll(".detail-view-field");
  inlineEditFields.forEach((field) => {
    observer.observe(field, { childList: true, subtree: true });
  });

  getCompanyData();
  appendButtons();
  document
    .getElementById("sendemail")
    .addEventListener("click", function (event) {
      event.preventDefault();
      sendEmailRequest();
    });
});

function applyTextareaStyling(textareaElement) {
  console.log(textareaElement);
  textareaElement.style.minHeight = "300px";
  textareaElement.style.width = "100%";
}

function appendButtons() {
  var approveButton = document.createElement("input");
  approveButton.className = "button primary";
  approveButton.type = "submit";
  approveButton.name = "button";
  var si_email_body = document.getElementById("si_email_body").innerHTML;
  var si_email_status = document.getElementById("si_email_status").value;
  if (
    !si_email_body &&
    (si_email_status === "not_written" ||
      si_email_status === "followup_not_written")
  ) {
    approveButton.value = "Write Email";
    approveButton.id = "writeemail";
    approveButton.title = "Write Email";
    approveButton.accessKey = "w";
    approveButton.addEventListener("click", function (event) {
      event.preventDefault();
      writeEmailRequest();
    });
  } else if (si_email_body) {
    approveButton.value = "Send Email";
    approveButton.id = "sendemail";
    approveButton.title = "Send Email";
    approveButton.accessKey = "a";
    approveButton.addEventListener("click", function (event) {
      event.preventDefault();
      sendEmailRequest();
    });
  }

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
      window.location.reload();
    })
    .catch((error) => {
      console.error("Error sending email request:", error);
    });
}
function writeEmailRequest() {
  var leadId = document.querySelector('input[name="record"]').value;

  fetch("index.php?module=Leads&action=si_writeEmail&to_pdf=1&id=" + leadId, {
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
      window.location.reload();
    })
    .catch((error) => {
      console.error("Error writing email request:", error);
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
        formatHref(
          "si_company_linkedin_profile",
          data.si_company_linkedin_profile
        );
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

function formatHref(elementId, val) {
  var hrefElement = document.getElementById(elementId);

  // Update the href and target attributes to open in a new tab
  hrefElement.href = val;
  hrefElement.target = "_blank";

  if (hrefElement.tagName === "INPUT") {
    hrefElement.innerHTML = val;
  } else {
    hrefElement.innerHTML =
      "<a target='_blank' href='" + val + "'>" + val + "</a>";
  }
}
