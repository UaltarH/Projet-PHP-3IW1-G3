import InputGroup from "./InputGroup.js";

// FirstPage of Installer, create an user

export let pseudo = () =>
  InputGroup(
    "pseudo",
    "Pseudo",
    "text",
    "pseudo",
    "pseudo",
    "Entrez votre pseudo",
    "Entrez votre pseudo"
  );
export let first_name = () =>
  InputGroup(
    "first_name",
    "Prénom",
    "text",
    "first_name",
    "first_name",
    "Entrez votre prénom",
    "Entrez votre prénom"
  );
export let last_name = () =>
  InputGroup(
    "last_name",
    "Nom",
    "text",
    "last_name",
    "last_name",
    "Entrez votre nom",
    "Entrez votre nom"
  );
export let email = () =>
  InputGroup(
    "email",
    "E-mail",
    "email",
    "email",
    "email",
    "Entrez votre adresse mail",
    "Entrez votre adresse mail d'aministrateur"
  );

export let phone_number = () =>
  InputGroup(
    "phone_number",
    "Numéro de téléphone",
    "tel",
    "phone_number",
    "phone_number",
    "Entrez votre numéro de téléphone",
    "Entrez votre numéro de téléphone"
  );

export let password = () =>
  InputGroup(
    "password",
    "Mot de passe",
    "password",
    "password",
    "password",
    "Entrez votre mot de passe",
    "Entrez votre mot de passe"
  );

export let passwordConfirm = () =>
  InputGroup(
    "passwordConfirm",
    "Confirmation du mot de passe",
    "password",
    "passwordConfirm",
    "passwordConfirm",
    "Confirmez votre mot de passe",
    "Confirmez votre mot de passe"
  );

// SecondPage of Installer, control the database

export let bddPrefix = () =>
  InputGroup(
    "bddPrefix",
    "Préfixe pour la base de données",
    "text",
    "bddPrefix",
    "bddPrefix",
    "",
    "Entrez le préfixe de votre base de données."
  );
export let bddName = () =>
  InputGroup(
    "bddName",
    "Nom de la base de données",
    "text",
    "bddName",
    "bddName",
    "",
    "Entrez le nom de votre base de données."
  );
export let bddUser = () =>
  InputGroup(
    "bddUser",
    "Votre nom d'utilisateur pour la BDD",
    "text",
    "bddUser",
    "bddUser",
    "",
    "Entrez le nom d'utilisateur pour la base de données."
  );
export let bddPassword = () =>
  InputGroup(
    "bddPassword",
    "Mot de passe pour accéder à la base de données",
    "password",
    "bddPassword",
    "bddPassword",
    "",
    "Entrez votre mot de passe pour accéder à la base de données."
  );
export let host = () =>
  InputGroup(
    "host",
    "Serveur de la base de données",
    "text",
    "host",
    "host",
    "",
    "Entrez le nom du serveur de la base de données."
  );
export let bddPort = () =>
  InputGroup(
    "bddPort",
    "Port de la base de données",
    "text",
    "bddPort",
    "bddPort",
    "",
    "Entrez le port de la base de données."
  );

export let siteName = () =>
  InputGroup(
    "siteName",
    "Nom du site",
    "text",
    "siteName",
    "siteName",
    "",
    "Entrez le nom du site souhaité, ce dernier doit être unique et ne pas être composé d'espace."
  );
