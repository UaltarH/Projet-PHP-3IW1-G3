export default function form_check(variable, conf) {
  if ("type" in conf && typeof variable !== conf.type)
    return { isValid: false, message: `Le type attendu est "${conf.type}"` };

  if ("required" in conf && Array.isArray(conf.required)) {
    for (const field of conf.required) {
      if (!(field in variable) || variable[field] === "") {
        return {
          isValid: false,
          message: `Le champ "${field}" est requis`,
        };
      }
    }
  }

  if ("properties" in conf) {
    for (const prop in conf.properties) {
      const validationResult = form_check(
        variable[prop],
        conf.properties[prop]
      );
      if (!validationResult.isValid) {
        return validationResult;
      }
    }
  }

  if ("value" in conf && variable !== conf.value) {
    if (typeof variable === "object") {
      if (JSON.stringify(variable) === JSON.stringify(conf.value)) {
        return { isValid: true };
      } else {
        return {
          isValid: false,
          message: `La valeur attendue est "${conf.value}"`,
        };
      }
    }
    return {
      isValid: false,
      message: `La valeur attendue est "${conf.value}"`,
    };
  }

  if ("enum" in conf && !conf.enum.includes(variable)) {
    if (typeof variable === "object") {
      const matchingValues = conf.enum.filter(
        (value) => JSON.stringify(value) === JSON.stringify(variable)
      );
      if (matchingValues.length > 0) {
        return { isValid: true };
      } else {
        return {
          isValid: false,
          message: `La valeur doit être l'une des suivantes : ${conf.enum.join(
            ", "
          )}`,
        };
      }
    }
    return {
      isValid: false,
      message: `La valeur doit être l'une des suivantes : ${conf.enum.join(
        ", "
      )}`,
    };
  }

  if ("min" in conf && variable < conf.min)
    return {
      isValid: false,
      message: `Le champ ${variable} doit être supérieure ou égale à ${conf.min}`,
    };

  if ("max" in conf && variable > conf.max)
    return {
      isValid: false,
      message: `Le champ ${variable} doit être inférieure ou égale à ${conf.max}`,
    };

  if ("format" in conf && conf.format === "email") {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(variable)) {
      return { isValid: false, message: "L'email n'est pas au format valide" };
    }
  }

  if ("format" in conf && conf.format === "tel") {
    const telRegex = /^0[1-9]([-. ]?[0-9]{2}){4}$/;
    if (!telRegex.test(variable)) {
      return {
        isValid: false,
        message: "Le numéro de téléphone n'est pas au format valide",
      };
    }
  }

  if ("format" in conf && conf.format === "password") {
    const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])/;
    if (!passwordRegex.test(variable)) {
      return {
        isValid: false,
        message:
          "Le mot de passe doit contenir au moins une minuscule, une majuscule, un chiffre et un caractère spécial",
      };
    }
  }

  return { isValid: true };
}
