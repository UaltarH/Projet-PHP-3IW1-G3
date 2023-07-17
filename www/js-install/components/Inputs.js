import InputGroup from "./InputGroup.js";

export let siteName = (error) =>
  InputGroup(
    "siteName",
    "Nom du site",
    "text",
    "siteName",
    "siteName",
    "",
    "Entrez le nom du site souhaité, ce dernier doit être unique et ne pas être composé d'espace."
  );
export let adminEmail = (error) =>
  InputGroup(
    "adminEmail",
    "E-mail de l'administrateur",
    "email",
    "adminEmail",
    "adminEmail",
    "",
    "Entrez votre adresse mail d'administrateur."
  );
export let password = (error) =>
  InputGroup(
    "password",
    "Mot de passe",
    "password",
    "password",
    "password",
    "",
    "Entrez votre mot de passe"
  );
export let bddPrefix = (error) =>
  InputGroup(
    "bddPrefix",
    "Préfixe pour la base de données",
    "text",
    "bddPrefix",
    "bddPrefix",
    "",
    "Entrez le préfixe de votre base de données."
  );
export let bddName = (error) =>
  InputGroup(
    "bddName",
    "Nom de la base de données",
    "text",
    "bddName",
    "bddName",
    "",
    "Entrez le nom de votre base de données."
  );
export let bddUser = (error) =>
  InputGroup(
    "bddUser",
    "Votre nom d'utilisateur pour la BDD",
    "text",
    "bddUser",
    "bddUser",
    "",
    "Entrez le nom d'utilisateur pour la base de données."
  );
export let bddPassword = (error) =>
  InputGroup(
    "bddPassword",
    "Mot de passe pour accéder à la base de données",
    "password",
    "bddPassword",
    "bddPassword",
    "",
    "Entrez votre mot de passe pour accéder à la base de données."
  );
export let host = (error) =>
  InputGroup(
    "host",
    "Serveur de la base de données",
    "text",
    "host",
    "host",
    "",
    "Entrez le nom du serveur de la base de données."
  );
export let bddPort = (error) =>
  InputGroup(
    "bddPort",
    "Port de la base de données",
    "text",
    "bddPort",
    "bddPort",
    "",
    "Entrez le port de la base de données."
  );
