/*****
 * Settings tabs scripts
 */
window.addEventListener("load", function () {
  // store tab variables
  var tabs = document.querySelectorAll("ul.nav-tabs > li");

  for (i = 0; i < tabs.length; i++) {
    tabs[i].addEventListener("click", switchTab);
  }

  function switchTab(event) {
    event.preventDefault();

    //console.log(event);
    //alert("test");
    document.querySelector("ul.nav-tabs li.active").classList.remove("active");
    document.querySelector(".tab-pane.active").classList.remove("active");

    var clickedTab = event.currentTarget;
    var anchor = event.target;
    var activePaneID = anchor.getAttribute("href");

    //console.log(anchor);

    clickedTab.classList.add("active");
    document.querySelector(activePaneID).classList.add("active");
  }
});
