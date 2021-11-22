document.addEventListener("DOMContentLoaded", function(){
    var button = new BX.UI.Button(
        {
            text: "Настройка",
            menu: {
                items: [
                    {
                        text: "Кого поблагодарили",
                        onclick: function() {
                            onclick: AJAXSend('whom')
                        }
                    },
                    {
                        text: "Кто поблагодарили",
                        onclick: function() {
                            onclick: AJAXSend('who')
                        }
                    },
                    { delimiter: true },
                    {
                        text: "Закрыть",
                        onclick: function(event, item) {
                            item.getMenuWindow().close();
                        }
                    }
                ],
                closeByEsc: true,
                offsetTop: 5,
            },
        });
    var container = document.getElementById("conroller-button");
    button.renderTo(container);
});
function AJAXSend(str) {
    BX.ajax.runComponentAction('filosoff:thanks', 'changeResult', {
        mode: 'class',
        data: {
            // str: str
        },
    }).then(function(response) {
        document.getElementById('container').innerHTML = response.data.html;
    });
}