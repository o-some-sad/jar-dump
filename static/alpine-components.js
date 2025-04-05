import Alpine from "https://esm.sh/alpinejs";
document.querySelectorAll("template[x-component]").forEach((template) => {
    const component = class extends HTMLElement {
        connectedCallback() {
            const defaults = template.getAttributeNames().filter((attr) =>
                attr.startsWith("x-") && attr !== "x-component"
            );
            defaults.forEach((attr) => {
                if (this.hasAttribute(attr)) return;
                this.setAttribute(attr, template.getAttribute(attr));
            });

            if(template.content.children.length > 1){
                console.warn("template has more than one root element, please consider make it only single element")
            }
            

            const shadowRoot = this.attachShadow({
                mode: "open",
            });
            shadowRoot.appendChild(template.content.cloneNode(true));
            Alpine.initTree(shadowRoot);
        }
        data() {
            console.log("a");

            return {
                "x-data": "{}",
            };
        }
    };
    const name = template.getAttribute("x-component");
    customElements.define(name, component);
});
