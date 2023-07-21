import {
  bddPrefix,
  siteName,
  siteDescription,
  adminEmail,
} from "../components/Inputs.js";
import { root } from "../index.js";
import generateStructure from "../core/generateStructure.js";
import form_check from "../utils/verification.js";

const validationSchema2 = {
  type: "object",
  properties: {
    bddPrefix: { type: "string", min: 2, max: 20 },
    siteName: { type: "string", min: 2, max: 20 },
    siteDescription: { type: "string", min: 2, max: 80 },
    adminEmail: { type: "string", min: 6, format: "email" },
  },
  required: ["bddPrefix", "siteName", "siteDescription", "adminEmail"],
};

export default function Page2() {
  window.addEventListener("load", () => {
    const currentPage = sessionStorage.getItem("currentPage");

    if (currentPage === "page2") {
      // Afficher la Page2
      console.log(currentPage);
      root.appendChild(generateStructure(Page2()));
    } else {
      // Afficher la Page1 (ou toute autre page par défaut)
      root.appendChild(generateStructure(Page1()));
    }
  });

  function isValid(event) {
    event.preventDefault();
    try {
      const formData = new FormData(event.currentTarget);
      const data = {};
      formData.forEach((value, key) => (data[key] = value));

      const validationResult = form_check(data, validationSchema2);

      let errorElement = document.getElementById("errorElement");
      if (!validationResult.isValid) {
        if (!errorElement) {
          errorElement = document.createElement("div");
          errorElement.id = "errorElement";
          errorElement.classList.add("alert", "alert-danger");
          errorElement.textContent = validationResult.message;
          root.appendChild(errorElement);
          errorElement.setAttribute("tabindex", "0");
          errorElement.focus();
        }
        errorElement.textContent = validationResult.message;
      } else {
        if (errorElement) {
          errorElement.remove();
        }
        fetch("/installer/set-database", {
          method: "POST",
          body: JSON.stringify(data),
          headers: {
            "Content-Type": "application/json; charset=utf-8",
          },
        })
          .then((response) => {
            if (response.ok) {
              return response.json();
            } else {
              throw new Error("Erreur lors de l'envoi du formulaire.");
            }
          })
          .then((responseData) => {
            if (responseData.success === true) {
              //fetch pour lancer l'initialisation du site (creation sql)
              fetch("/installer/init-site", {
                method: "POST",
                body: JSON.stringify(data),
                headers: {
                  "Content-Type": "application/json; charset=utf-8",
                },
              })
                .then((response) => {
                  if (response.ok) {
                    return response.json();
                  } else {
                    throw new Error("Erreur lors de l'envoi du formulaire.");
                  }
                })
                .then((responseData) => {
                  if (responseData.success === true) {
                    // rediriger vers login
                    window.location.href = "/login";
                  } else {
                    console.log(responseData);
                  }
                });
            }
          });
      }
    } catch (error) {
      console.error(error);
    }
  }

  return {
    type: "div",
    attributes: { id: "page2", class: "container" },

    children: [
      {
        type: "h1",
        children: ["Bienvenue sur La Carte chance !"],
        attributes: {
          class: "text-center p-3 text-primary",
        },
      },
      {
        type: "p",
        children: ["Merci d'installer notre CMS."],
        attributes: { class: "mb-2 p-2" },
      },
      {
        type: "p",
        children: [
          "Veuillez suivre les étapes ci-dessous pour finaliser la création de votre site.",
        ],
        attributes: { class: "mb-4 p-2" },
      },

      {
        type: "form",
        attributes: {
          method: "post",
          style: { display: "flex", flexDirection: "column" },
          class: "container",
        },
        events: {
          submit: isValid,
        },
        children: [
          bddPrefix(),
          siteName(),
          siteDescription(),
          adminEmail(),
          {
            type: "button",
            attributes: { type: "submit", class: "btn btn-primary text-white" },
            children: ["Suivant"],
          },
        ],
      },
    ],
  };
}
