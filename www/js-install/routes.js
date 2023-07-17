import Exo from "./pages/Exo.js";
import Page1 from "./pages/Page1.js";
import Page2 from "./pages/Page2.js";

export default {
  "/page1": Page1,
  "/page2": Page2,
  //  "/page3": {
  //    component: Page3,
  //    attributes: {
  //      id: 5,
  //    },
  //  },
  "/exo": {
    component: Exo,
    attributes: {
      cols: 10,
    },
  },
};
