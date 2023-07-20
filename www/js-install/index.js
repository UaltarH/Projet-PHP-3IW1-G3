import BrowserRouter from "./components/BrowserRouter.js";
import routes from "./routes.js";
import generateStructure from "./core/generateStructure.js";
import Page1 from "./pages/Page1.js";
import Exo from "./pages/Exo.js";

export const root = document.getElementById("root");
// BrowserRouter(routes, root, root.dataset.baseurl);

root.appendChild(generateStructure(Page1()));
// root.appendChild(
//   generateStructure(Exo({ rows: 10, cols: 10 }), root.dataset.baseurl)
// );

//const CustomButton = function (title, onClick) {
//  return {
//    type: "button",
//    attributes: {
//      class: "btn-red",
//    },
//    events: {
//      click: onClick,
//    },
//    children: [title],
//  };
//};
//
// const test = {
//   type: "", // div, ul, li
//   attributes: {
//     id: 3,
//     "data-position": "3-1",
//   },
//   children: [
//     "coucou",
//     {
//       type: CustomButton,
//       props: {
//         title: "Coucou",
//         onClick: () => {},
//       },
//     },
//   ],
//   events: {
//     click: () => {},
//   },
// };

// MiniReact.createElement("div", { id: "page1" }, [
//   MiniReact.createElement("h1", {}, ["Page 1"]),
// ]);

// root.appendChild(generateStructure(Page2));

// BrowserRouter(routes, root);

//function BrowserRouter({ routes }) {
//  window.history.pushState = function (state, title, path) {
//    oldPushState.call(window.history, state, title, path);
//    window.dispatchEvent(new Event("popstate"));
//  };
//  function generate() {
//    this.setState({ path: window.location.pathname });
//  }
//  window.addEventListener("popstate", generate);
//  return {
//    type: routes[this.state.path].path,
//    attributes: routes[this.state.path].props,
//  };
//}
