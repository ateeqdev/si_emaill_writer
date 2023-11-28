function createStyledTextarea(text) {
  const textarea = document.createElement("textarea");
  textarea.textContent = text;
  textarea.style.cssText = "min-height: 300px; width: 100%;";
  return textarea;
}

function createButton(id, value, title, accessKey, clickHandler) {
  const button = document.createElement("input");
  button.className = "button primary";
  button.type = "submit";
  button.name = "button";
  button.value = value;
  button.id = id;
  button.title = title;
  button.accessKey = accessKey;
  button.addEventListener("click", clickHandler);
  return button;
}

function handleEmailRequest(apiEndpoint, successMessage) {
  showLoader();

  fetch(apiEndpoint, {
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
      console.debug(successMessage);
      window.location.reload();
    })
    .catch((error) => {
      console.error(`Error ${successMessage.toLowerCase()}:`, error);
    })
    .finally(() => {
      hideLoader();
    });
}

function handleCompanyData(leadId) {
  const apiEndpoint = `index.php?module=si_Campaigner&action=getCompanyData&to_pdf=1&leadId=${leadId}`;
  fetch(apiEndpoint)
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        throw new Error("Request failed");
      }
    })
    .then((data) => {
      if (data) {
        const linkedinProfile = data.si_company_linkedin_profile;
        const companyDescription = data.si_company_description;

        if (linkedinProfile) {
          formatHref("si_company_linkedin_profile", linkedinProfile);
        }

        if (companyDescription) {
          const companyBioElement = document.getElementById(
            "si_company_description"
          );
          companyBioElement.innerHTML = companyDescription;
        }
      }
    })
    .catch((error) => {
      console.error("Error fetching data:", error);
    });
}

function showLoader() {
  const overlay = document.createElement("div");
  overlay.className = "overlay";
  const loader = document.createElement("div");
  loader.className = "si_loader";
  document.body.appendChild(overlay);
  document.body.appendChild(loader);
}

function hideLoader() {
  const loader = document.querySelector(".si_loader");
  const overlay = document.querySelector(".overlay");

  if (loader) {
    loader.remove();
  }

  if (overlay) {
    overlay.remove();
  }
}

function styleLoader() {
  const cssRules = `
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
    }

    .si_loader {
      border: 8px solid #fff;
      border-top: 8px solid #3498db;
      border-radius: 50%;
      width: 150px;
      height: 150px;
      animation: custom-spin 1s linear infinite;
      position: absolute;
      top: 50%;
      left: 50%;
      margin-top: -75px;
      margin-left: -75px;
      z-index: 9999;
    }

    @keyframes custom-spin {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
  `;

  const styleElement = document.createElement("style");
  styleElement.appendChild(document.createTextNode(cssRules));
  document.head.appendChild(styleElement);
}

document.addEventListener("DOMContentLoaded", function () {
  formatHref(
    "si_linkedin_profile",
    document.getElementById("si_linkedin_profile").innerText
  );

  const observer = new MutationObserver((mutationsList) => {
    for (const mutation of mutationsList) {
      if (mutation.type === "childList" && mutation.addedNodes.length > 0) {
        for (const node of mutation.addedNodes) {
          if (node instanceof HTMLElement) {
            const textareas = node.querySelectorAll("textarea");
            textareas.forEach((textarea) => {
              textarea.style.cssText = "min-height: 300px; width: 100%;";
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
  styleLoader();
});

function appendButtons() {
  const si_email_body = document.getElementById("si_email_body").innerHTML;
  const si_email_status = document.getElementById("status").value;

  let buttonConfig = {};

  if (!si_email_body) {
    if (si_email_status === "ready_for_email") {
      buttonConfig = {
        id: "writeemail",
        value: "Write First Email",
        title: "Write an Introductory Email",
        accessKey: "w",
        clickHandler: writeEmailRequest,
      };
    } else if (si_email_status === "followup_required") {
      buttonConfig = {
        id: "writeemail",
        value: "Write a Followup Email",
        title: "Write a Followup Email",
        accessKey: "w",
        clickHandler: writeEmailRequest,
      };
    }
  } else {
    // Add an "Approve" button when the status is "ready_for_approval" or "followup_written"
    if (si_email_status === "ready_for_approval") {
      buttonConfig = {
        id: "approve",
        value: "Approve Email",
        title: "Approve the Introductory Email",
        accessKey: "p",
        clickHandler: sendApprovalRequest,
      };
    }
    if (si_email_status === "followup_written") {
      buttonConfig = {
        id: "approve",
        value: "Approve the Followup Email",
        title: "Approve the Followup Email",
        accessKey: "p",
        clickHandler: sendApprovalRequest,
      };
    } else {
      buttonConfig = {
        id: "sendemail",
        value: "Send Email",
        title: "Send Email",
        accessKey: "a",
        clickHandler: sendEmailRequest,
      };
    }
  }

  const buttonsElement =
    document.querySelector("ul.nav.nav-tabs") ||
    document.querySelector(".buttons");

  if (buttonConfig && buttonConfig.id)
    buttonsElement.appendChild(
      createButton(
        buttonConfig.id,
        buttonConfig.value,
        buttonConfig.title,
        buttonConfig.accessKey,
        buttonConfig.clickHandler
      )
    );
}

function sendApprovalRequest() {
  const leadId = document.querySelector('input[name="record"]').value;
  const apiEndpoint = `index.php?module=Leads&action=si_approveEmail&to_pdf=1&id=${leadId}`;
  handleEmailRequest(apiEndpoint, "Approval Request Successful");
}

function sendEmailRequest() {
  const leadId = document.querySelector('input[name="record"]').value;
  const apiEndpoint = `index.php?module=Leads&action=si_sendEmail&to_pdf=1&id=${leadId}`;
  handleEmailRequest(apiEndpoint, "Email Request Successful");
}

function writeEmailRequest() {
  const leadId = document.querySelector('input[name="record"]').value;
  const apiEndpoint = `index.php?module=Leads&action=si_writeEmail&to_pdf=1&id=${leadId}`;
  handleEmailRequest(apiEndpoint, "Writing Email Request Successful");
}

function getCompanyData() {
  const leadId = document.querySelector('input[name="record"]').value;
  handleCompanyData(leadId);
}

function formatHref(elementId, val) {
  const hrefElement = document.getElementById(elementId);
  hrefElement.href = val;
  hrefElement.target = "_blank";
  if (hrefElement.tagName === "INPUT") {
    hrefElement.innerHTML = val;
  } else {
    hrefElement.innerHTML = `<a target='_blank' href='${val}'>${val}</a>`;
  }
}
