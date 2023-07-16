import { BrowserLink } from "../components/BrowserRouter.js";
import form_check from "../utils/verification.js";
import {
  siteName,
  adminEmail,
  password,
  bddPrefix,
  bddName,
  bddUser,
  bddPassword,
  host,
  bddPort,
} from "../components/Inputs.js";

const validationSchema = {
  type: "object",
  properties: {
    siteName: { type: "string", min: 3, max: 20 },
    adminEmail: { type: "string", format: "email" },
    password: { type: "string", min: 8, format: "password" },
    bddPrefix: { type: "string", min: 3, max: 20 },
    bddName: { type: "string", min: 3, max: 20 },
    bddUser: { type: "string", min: 3, max: 20 },
    bddPassword: { type: "string", min: 8, format: "password" },
    host: { type: "string", min: 3, max: 20 },
    bddPort: { type: "number", min: 3, max: 20 },
  },
  required: [
    "siteName",
    "adminEmail",
    "password",
    "bddPrefix",
    "bddName",
    "bddUser",
    "bddPassword",
    "host",
    "bddPort",
  ],
};

export default function Page1() {
  function isValid(event) {
    event.preventDefault();
    const formData = new FormData(event.currentTarget);
    const data = {};
    formData.forEach((value, key) => (data[key] = value));
    console.log(data);

    // const formDataSiteName = { siteName: siteName };
    // const formDataSiteNameJson = JSON.stringify(formDataSiteName);
    // localStorage.setItem("formDataSiteName", formDataSiteNameJson);
    // const formDataUser = { adminEmail: adminEmail, password: password };
    // const formDataBdd = {
    //   bddPrefix: bddPrefix,
    //   bddName: bddName,
    //   bddUser: bddUser,
    //   bddPassword: bddPassword,
    //   host: host,
    //   bddPort: bddPort,
    // };

    const validationResult = form_check(data, validationSchema);
    let errorElement = document.getElementById("errorElement");

    if (!validationResult.isValid) {
      if (!errorElement) {
        errorElement = document.createElement("div");
        errorElement.id = "errorElement";
        errorElement.classList.add("alert", "alert-danger");
        errorElement.textContent = validationResult.message;
        document.getElementById("bdd").appendChild(errorElement);
      }
      errorElement.textContent = validationResult.message;
    } else {
      console.log("Le formulaire est valide");

      if (errorElement) {
        errorElement.remove();
      }
    }
  }

  return {
    type: "div",
    attributes: {
      id: "page1",
      class: "container bg-light  ",
    },
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
          {
            type: "fieldset",
            attributes: { id: "website", class: "border p-2" },
            children: [...siteName()],
          },
          { type: "hr", attributes: { class: "hr" } },
          {
            type: "fieldset",
            attributes: { id: "user", class: "border p-2" },
            children: [...adminEmail(), ...password()],
          },
          { type: "hr", attributes: { class: "hr" } },
          {
            type: "fieldset",
            attributes: { id: "bdd", class: "border p-2" },
            children: [
              ...bddPrefix(),
              ...bddName(),
              ...bddUser(),
              ...bddPassword(),
              ...host(),
              ...bddPort(),
            ],
          },

          {
            type: "button",
            attributes: { type: "submit", class: "btn btn-primary text-white" },
            children: ["Installer"],
          },
        ],
      },
    ],
  };
}
