var toasterTitle = "Well done!";
var toasterMessage = "Some thing went wrong";

function successToaster() {
    $(".toast").addClass("toast-success");
    $(".toast-title").text(toasterTitle);
    $(".toast-message").text(toasterMessage);
    $("#toast-container").show(300, "swing");
}

function errorToaster() {
    $(".toast").addClass("toast-error");
    $(".toast-title").text(toasterTitle);
    $(".toast-message").text(toasterMessage);
    $("#toast-container").show(300, "swing");
}

function ternimateToaster() {
    $("#toast-container").hide(300, 'swing');
}

function onloader() {
    document.getElementById("overlay").style.display = "flex";
}

function offloader() {
    document.getElementById("overlay").style.display = "none";
}

function redirectUrl(url) {
    setTimeout(function() {
        window.location.href = url;
    }, 2000);
}

function pcsSum() {
    let item_pcs = 0;
    $(".item_pcs").each(function() {
        item_pcs += Number($(this).val());
    });
    $("#pcs").val(item_pcs);
}

function qtySum() {
    let item_pcs = 0;
    $(".item_qt").each(function() {
        item_pcs += Number($(this).val());
    });
    $("#qty_").val(item_pcs);
    console.log("hell");
}

function checkDebitCredit() {
    var credit1 = $(".credit1").val();
    var credit2 = $(".credit2").val();
    var debit1 = $(".debit1").val();
    var debit2 = $(".debit2").val();
    if (credit1 !== "") {
        $(".credit2").val("");
    } else if (credit2 !== "") {
        $(".credit1").val("");
    }

    if (debit1 !== "") {
        $(".debit2").val("");
    } else if (debit2 !== "") {
        $(".debit1").val("");
    }

    if (
        (credit1 !== "" && debit1 !== "") ||
        (credit2 !== "" && debit2 !== "")
    ) {
        $(".credit1").val("");
        $(".credit2").val("");
        $(".debit1").val("");
        $(".debit2").val("");
    }
}

function calculateInvoiceSum() {
    var net_total = 0;
    $(".item_price").each(function(index) {
        var item_price = $(this).closest("tr").find(".item_price").val();
        if ($(this).closest("tr").find(".additional_rate").length) {
            item_price = (
                parseFloat(item_price) +
                parseFloat($(this).closest("tr").find(".additional_rate").val())
            ).toFixed(2);
        }
        var item_qty = $(this).closest("tr").find(".item_qty").val();
        var amount = item_price * item_qty;
        $(this).closest("tr").find(".amount").val(amount);
        net_total += amount;
    });

    $("#net_total").val(net_total);
}

function deductEmployeeAmount() {
    var net_total = 0;
    var actual_production_amount = $("#actual_production_amount").text();
    var additional_amount = $("#additional_amount").val();
    var amount_type = $("input[name='amount_type']:checked").val();
    var gross_total = actual_production_amount * amount_type;
    $("#gross_total").val(gross_total);
    var deduction_amount = $("#deduction_amount").val();
    if (gross_total >= deduction_amount) {
        net_total = gross_total - deduction_amount;
    }
    net_total = net_total + Number(additional_amount);
    $("#net_total").val(net_total);
}

$(".bank_check_toggle").click(function(e) {
    $("#show_hide_inps").show();
});
$(".cash").click(function() {
    let first = document.querySelectorAll(".check_number");
    let second = document.querySelectorAll(".bank_name");
    first.forEach((element, index) => {
        first[index].value = "";
        second[index].value = "";
    });

    $(".tr-content:gt(0)").remove();
    $("#show_hide_inps").hide();
});

function calculateLedgerSum() {
    var net_total = 0;
    $(".amountReceipt").each(function(index) {
        var amountReceipt = $(this).val();
        net_total += +amountReceipt;
    });
    $("#net_total").val(net_total);
}

function CalculateEmployeeSalary(employeeid) {
    var total_present = 0;
    var total_absent = 0;
    var total_leave = 0;
    var total_half_days = 0;
    var total_holidays = 0;
    $(".attendece_" + employeeid).each(function(index) {
        var attendeceString = $(this).val();
        var attendeceSplitedArray = attendeceString.split("~");
        var attendece = attendeceSplitedArray[2];
        if (attendece == 1) {
            total_present += 1;
        }
        if (attendece == 2) {
            total_absent += 1;
        }
        if (attendece == 3) {
            total_leave += 1;
        }
        if (attendece == 4) {
            total_half_days += 1;
        }
        if (attendece == 5) {
            total_holidays += 1;
        }
    });

    $("#total_present_" + employeeid).val(total_present);
    $("#total_absent_" + employeeid).val(total_absent);
    $("#total_leave_" + employeeid).val(total_leave);
    $("#total_half_days_" + employeeid).val(total_half_days);
    $("#total_holidays_" + employeeid).val(total_holidays);
    var basic_salary = $("#total_basic_salary_" + employeeid).val();
    var total_working_days =
        Number(total_present) + Number(total_leave) + Number(total_holidays);
    //total_working_days = total_working_days - total_absent;
    total_working_days = (
        parseFloat(total_working_days) + parseFloat(total_half_days / 2)
    ).toFixed(2);
    $("#total_working_days_" + employeeid).val(total_working_days);
    var gross_salary = parseFloat(basic_salary * total_working_days).toFixed(2);
    $("#gross_salary_" + employeeid).val(gross_salary);
    var total_deduction = $("#total_deduction_" + employeeid).val();
    var net_salary = parseFloat(gross_salary - total_deduction).toFixed(2);
    $("#net_salary_" + employeeid).val(net_salary);
}

function removeRow($this) {
    $($this).closest("tr").remove();
}

function deleteRecord(route) {
    $.ajax({
        type: "GET",
        url: route,
        data: {}, // serializes the form's elements.
        success: function(response) {
            if (response.success == true) {
                offloader();
                toasterTitle = 'Well done!';
                toasterMessage = response.message;
                successToaster();
                redirectUrl(response.redirectUrl);
            } else {
                offloader();
                toasterTitle = "Error !";
                toasterMessage = response.message;
                errorToaster();
            }
        },
        error: function(response) {
            var response = JSON.parse(response.responseText);
            offloader();
            $.each(response.errors, function(key, value) {
                toasterMessage = value;
            });
            toasterTitle = "Error !";
            errorToaster();
        },
    });
}
// Open window for pdf
$(".pdf").click(function(e) {
    e.preventDefault();
    let href = $(this).attr("href");
    window.open(
        href,
        "_blank",
        "top=100, left=100, width=800, height=500, menubar=yes,toolbar=yes, scrollbars=yes, resizable=yes"
    );
});
//Employee Module Js
function getItemEmployeeRates(itemid) {
    onloader();
    $.ajax({
        type: "GET",
        url: "/getItemEmployeeRates/" + itemid + "",
        headers: {
            "X-CSRF-Token": "{{ csrf_token() }}",
        },
        data: {}, // serializes the form's elements.
        success: function(response) {
            $("#production_table tbody").empty();
            if (response.success == true) {
                var i = 1;
                $.each(response.data, function(key, value) {
                    var amount = (
                        Number($("#production_qty").val()) *
                        parseFloat(value.additional_rate)
                    ).toFixed(2);
                    var item_price = (
                        parseFloat(value.rate) +
                        parseFloat(value.additional_rate)
                    ).toFixed(2);
                    var html = "";
                    html += "<tr>";
                    html += '<td class="text-center">';
                    html += i;
                    html += "</td>";
                    html += '<td class="text-center text-muted">';
                    html +=
                        '<input name="employee_name[]" id="employee_name" placeholder="Employee Name" value="' +
                        value.employee_name +
                        '" type="text" class="form-control">';
                    html +=
                        '<input name="employee_id[]" id="employee_id" placeholder="employee_id" value="' +
                        value.employee_id +
                        '" type="hidden" class="form-control">';
                    html += "</td>";
                    html +=
                        '<td class="text-center"><input name="rate[]" id="rate" placeholder="Rate" value="' +
                        value.rate +
                        '" type="text" class="form-control rate" readonly></td>';
                    html +=
                        '<td class="text-center"><input name="additional_rate[]" id="additional_rate" placeholder="additional rate" value="' +
                        value.additional_rate +
                        '" type="text" class="form-control additional_rate" readonly></td>';

                    html +=
                        '<td class="text-center"><input name="item_price[]" id="item_price" placeholder="Rate" value="' +
                        item_price +
                        '" type="text" class="form-control item_price" readonly></td>';
                    html +=
                        '<td class="text-center"><input name="item_qty[]" id="item_qty" placeholder="Quantity" value="' +
                        $("#production_qty").val() +
                        '" type="text" class="form-control item_qty" readonly></td>';
                    html +=
                        '<td class="text-center"><input name="amount[]" id="amount" placeholder="Total Amount" value="' +
                        amount +
                        '" type="text" class="form-control amount" readonly></td>';
                    html += "</tr>";
                    if ($("#single_employee_id").val() == "") {
                        if (value.production_method == 0) {
                            $("#production_table tbody").append(html);
                        }
                    } else {
                        if (
                            value.employee_id == $("#single_employee_id").val()
                        ) {
                            $("#production_table tbody").append(html);
                        }
                    }

                    i++;
                });
                calculateInvoiceSum();
                offloader();
                //toasterTitle = 'Well done!';
                //toasterMessage = response.message;
                //successToaster();
                //redirectUrl(response.redirectUrl);
            } else {
                offloader();
                //toasterTitle = 'Error !';
                //toasterMessage = response.message;
                //errorToaster();
            }
        },
        error: function(response) {
            var response = JSON.parse(response.responseText);
            offloader();
            $.each(response.errors, function(key, value) {
                toasterMessage = value;
            });
            toasterTitle = "Error !";
            errorToaster();
        },
    });
}

function updateProductionQty() {
    $(".item_qty").val($("#production_qty").val());
    calculateInvoiceSum();
}
$(document).ready(function() {
    $(".js-example-basic-single").select2();
    $(".add_row").click(function() {
        var e = $(".new_row").find(".sr_no");
        var sr_no = Number(e.text()) + Number(1);
        e.text(sr_no);
        $($(".new_row").html()).insertBefore($(".btn-add-new"));
        $(".select-drop-down").last().select2();
        $(".new_row").find(".sr_no").text(sr_no);
    });

    $("body").on("click", ".removeRow", function() {
        console.info("calling");
        $(this).closest("tr").remove();
    });

    // $('#show_hide_inps').hide();

    $(".Q-form").submit(function(e) {
        e.preventDefault();
        var form = $(this);
        var url = form.attr("action");
        var formData = new FormData(this);
        onloader();
        $.ajax({
            type: "POST",
            url: url,
            data: formData, // serializes the form's elements.
            cache: false,
            contentType: false,
            processData: false,
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
            success: function(response) {
                if (response.success == true) {
                    offloader();
                    toasterTitle = "Well done!";
                    toasterMessage = response.message;
                    successToaster();
                    setTimeout(() => {
                        ternimateToaster();
                    }, 4000);
                    if (response.hasOwnProperty("print")) {
                        openPopUrl(response.print);
                    }
                    if (response.redirect == false) {
                        loadNextInvoiceNumber();
                        return;
                    }
                    redirectUrl(response.redirectUrl);
                } else {
                    offloader();
                    toasterTitle = "Error !";
                    toasterMessage = response.message;
                    errorToaster();
                }
            },
            error: function(response) {
                var response = JSON.parse(response.responseText);
                offloader();
                $.each(response.errors, function(key, value) {
                    toasterMessage = value;
                });
                toasterTitle = "Error !";
                errorToaster();
                setTimeout(() => {
                    ternimateToaster();
                }, 4000);
            },
        });
    });
});

function openPopUrl(url) {
    window.open(url, "_blank", "toolbar=yes,scrollbars=yes,resizable=yes,top=500,left=500,width=1000,height=700");
}
// Category Check
function checkCategory($this) {
    let category = $($this).val();
    if (category == 5) {
        $("#menu-hide-show").show();
    } else {
        $("#menu-hide-show").hide();
        // $("#itemMenu").selectedIndex = 0;
        document.getElementById("itemMenu").value = "";
    }
}




// ************************* Multi Inovice js code *************************** //
$(document).ready(function() {
            // Appeding items to selected side bar
            $(".item-info").click(function() {
                let check = false;
                let checkCount = Number($(this).find(".id").val());
                // console.log(checkCount);
                $(".selected-items")
                    .find(".added-item")
                    .each(function() {
                        // console.log('in', Number($(this).find('.checkCount').val()));
                        if (Number($(this).find(".checkCount").val()) == checkCount) {
                            console.log("hello");
                            check = true;
                        }
                    });
                if (check == true) return;
                let item_name = $(this).find(".card-title").text();
                let item_price = $(this).find(".card-price").text();
                let id = $(this).find(".id").val();
                let item = `        <div class="added-item position-relative">

                          <div class="row justify-content-between align-items-center">
                              <div class="col-4"><b>${item_name}</b><br>
                              </div>
                              <div class="qty-manage col-1  qty-decrease" onclick="decrease(this);">&#8722;</div>
                              <div class="pt-1 col-2 text-center"><input type="text" min="1" readonly value="1" class="qty-box"></div>
                              <div class="qty-manage col-1 qty-increase" onclick="increase(this);">+</div>
                              <div class="price-against_item col-2"><input type="text" value="${item_price}" name="item_price[]" onchange="recalculation(this);" class="invoice_price price--box" /></div>
                              <div class="price-against_item col-2"><b class="item_total_price">${item_price}</b></div>
                              <input type="hidden" value="${item_price}" name="amount[]" class="totalAmount" />
                              <input type="hidden" value="1" name="item_qty[]" class="itemQty" />
                          </div>
                          <span onclick="deleteItem(this);" class="cross">&#x274C;</span>
                          <hr>
                          <input type="hidden" name="item_id[]" class="checkCount" value="${id}">
                      </div>`;

                $(".selected-items").append(item);

                let gross_total = 0;
                $(".selected-items")
                    .find(".added-item")
                    .each(function() {
                        let individual_pirce = Number(
                            $(this)
                            .closest(".added-item")
                            .find(".item_total_price")
                            .text()
                        );
                        gross_total += individual_pirce;
                    });
                $(".gross_amount").text(gross_total);
                gross_total = 0;
                calculateSum();
            });

            // Show invoice list in modal (ajax)
            $("#showInvoiceList").click(function() {
                        let url = `${window.location.origin}/invoiceList/data`;
                        $.ajax({
                                    type: "GET",
                                    url: url,
                                    dataType: "json",
                                    success: function(response) {
                                            if (response.code == 200) {
                                                let html = "";
                                                let i = 1;
                                                response.data.forEach((element) => {
                                                            html += `        <tr class="${element.status == 2 ? "text-muted" : ""
                            } myTr">
                                                                  <td class="text-center text-muted">${i}</td>
                                                                  <td class="text-center text-muted invoiceNumber">${element.invoice_number
                            }</td>
                                                                  <td class="text-center">${element.customer_name
                            }</td>
                                                                  <td class="text-center">${element.net_total
                            } </td>
                                                                  <td class="text-center">${element.net_pcs
                            } </td>
                                                                  <td class="text-center"> ${element.net_qty
                            }</td>
                                                                  <td class="text-center">${element.invoice_date
                            }</td>
                                                                  <td class="text-center table-no">${element.table_no
                            }</td>
                                                                  <td class="text-center">
                                                                  ${element.status ==
                                2
                                ? `Refunded`
                                : `
                                                                  <div class="mb-2 mr-2 btn-group">
                                                                      <button class="btn btn-outline-success">Edit</button>
                                                                      <button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle-split dropdown-toggle btn btn-outline-success"><span class="sr-only">Toggle Dropdown</span>
                                                                      </button>
                                                                      <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu" x-placement="bottom-start" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(68px, 33px, 0px);">
                                                                          <a href="#"><button type="button" onclick="appendDateTosideBar(${element.id});" tabindex="0" class="dropdown-item">Edit</button></a>
                                                                          <a href="#"><button type="button" onclick="invoiceRefund(${element.id})" tabindex="0" class="dropdown-item">Refund</button></a>
                                                                          <a href="${window.location.origin}/sales/pdf/${element.id})}}" class="pdf"><button type="button" tabindex="0" class="dropdown-item">PDF</button></a>
                                                                  
                                                                  
                                                                      </div>
                                                                  </div>`
                            }
                                                                  </td>
                                                              </tr>`;
                        i++;
                    });
                    $(".invoiceList").html(html);
                } else {
                    $(".invoiceList").html(
                        `<div><b>${response.message}</b></div>`
                    );
                }
            },
        });
    });

    // Add Amount Buttons
    $(".add-amount").click(function () {
        let amount = Number($(this).find("span").text());
        let paid_amount = Number($(".paid_amount").val());
        $(".paid_amount").val(paid_amount + amount);
        calculateSum();
    });
    // commsiion amount net total * commission/100

   

    $(".searchInvoice").submit(function (e) {
        e.preventDefault();
        myFunction();
    });
});

// Multi Invoice Calculation (function)
function calculateSum() {
    let net_qty = 0;
    $(".selected-items")
        .find(".added-item")
        .each(function () {
            let qty = Number(
                $(this).closest(".added-item").find(".qty-box").val()
            );
            net_qty += qty;
            $("#netQty").val(net_qty);
        });
    let grossTotal = Number($(".gross_amount").text());
    $('#gross_total').val(grossTotal);
    let discount = Number($(".item_discount").val());
    
    let commission_persent=Number($('#commission_persent').val()); 
    console.info(commission_persent);   
    let tax = Number($(".item_tax").val());
    let commission_amount=((grossTotal*commission_persent)/100).toFixed(3);
    let TotalCommission=grossTotal-commission_amount;
    let zakat=Number((TotalCommission*2.5)/100).toFixed(3);
    let sadqa=Number((TotalCommission*1)/100).toFixed(3);
    let dicount_amount = Number((grossTotal * discount)) / 100;
    $(".discount_amount").html(dicount_amount);
    let tax_amount = (grossTotal * tax) / 100;
    $(".tax_amount").html(tax_amount);
    grossTotal -= dicount_amount;
    grossTotal += tax_amount;
    
    $(".net_total").text(grossTotal.toFixed(2));
    $('.commission_amount').val(commission_amount);
    $('.zakat').val(zakat);
    $('.sadqa').val(sadqa);
    $("#netTotal").val(grossTotal.toFixed(2));
    let enteredAmount = Number($(".paid_amount").val());
    let remainder = enteredAmount - grossTotal;
    $(".remainder").text(remainder.toFixed(1));
    $("#remainder").val(remainder.toFixed(1));
    grossTotal = 0;
}
function invoiceRefund(id) {
    let url = `${window.location.origin}/invoice/refund/${id}`;
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (response) {
            if (response.code == 200) {
                $(".close").click();
            } else {
            }
        },
    });
}

function appendDateTosideBar(id) {
    let url = `${window.location.origin}/invoice/data`;
    $("#invoice_id").val(id);
    $.ajax({
        type: "POST",
        url: url,
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        data: { invoiceId: id },
        dataType: "json",
        success: function (response) {
            console.log(response);
            if (response.code == 200) {
                let html = "";
                let i = 1;
                response.data.forEach((element) => {
                    html += `<div class="added-item position-relative">

                  <div class="row justify-content-between align-items-center">
                      <div class="col-4"><b>${element.name}</b><br>
                      </div>
                      <div class="qty-manage col-1  qty-decrease" onclick="decrease(this);">&#8722;</div>
                      <div class="pt-1 col-2 text-center"><input type="text" min="1" readonly value="${element.item_qty
                        }" class="qty-box"></div>
                      <div class="qty-manage col-1 qty-increase" onclick="increase(this);">+</div>
                      <div class="price-against_item col-2"><input type="text" value="${element.item_price}" name="item_price[]" onchange="recalculation(this);" class="invoice_price price--box" /></div>
                      <div class="price-against_item col-2"><b class="item_total_price">${element.item_price * element.item_qty
                        }</b></div>
                      <input type="hidden" value="${element.item_price * element.item_qty
                        }" name="amount[]" class="totalAmount" />
                      <input type="hidden" value="${element.item_qty
                        }" name="item_qty[]" class="itemQty" />
                  </div>
                  <hr>
                  <input type="hidden" name="item_id[]" class="checkCount" value="${element.item_id
                        }">
              </div>`;
                    i++;
                });
                $(".close").click();
                $(".invoiceStatus").html("UPDate");
                $("#tax").val(response.invoice_tax);
                $("#discount").val(response.invoice_discount);
                $("#gross_total").val(response.invoice_gross_total);
                $(".gross_amount").val(response.invoice_gross_total);
                $("#netTotal").val(response.invoice_net_total);
                $(".net_total").val(response.invoice_net_total);
                $("#paid").val(response.invoice_paid_amount);
                $("#remainder").val(response.invoice_remainder);
                $(".remainder").val(response.invoice_remainder);
                $("#table_no").val(response.invoice_table_no);
                $(".invoiceStatus").html("UPDate");
                $(".invoiceStatus").html("UPDate");
                $("#invoice_number").val(response.invoice_number);
                $("#show-invoice-number").html(response.invoice_number);
                $("#invoice_date").val(response.invoice_date);
                $(".Q-form").attr(
                    "action",
                    window.location.origin + "/sales/update"
                );
                $(".selected-items").html(html);
                $(".saveUpdateBtn").html("Update");
                continueCounting();
            } else {
                $(".close").click();
                $(".selected-items").html(
                    `<div><b>${response.message}</b></div>`
                );
            }
        },
        error: function () {
            $(".close").click();
            $(".selected-items").html(`<div><b>Some Error!</b></div>`);
        },
    });
}

// Function to load next invoice number after save
function loadNextInvoiceNumber() {
    $('#table_no').val('');
    $('#discount').val('');
    $('#tax').val('');
    $('#paid').val('');
    let url = `${window.location.origin}/invoice/getNumber`;
    $.ajax({
        type: "GET",
        url: url,
        dataType: "json",
        success: function (response) {
            if (response.success == true) {
                $('.close').click();
                $('#invoice_number').val(response.invoice_number);
                $('#show-invoice-number').html(response.invoice_number);
                $('.invoiceStatus').html('new');
                $('.saveUpdateBtn').html('Save');
                $('.selected-items').html('');
                continueCounting();
            } else {
                $(".close").click();
                $(".selected-items").html(
                    `<div><b>${response.message}</b></div>`
                );
            }
        },
    });
}
// Search Invoice List
function myFunction() {
    var input, filter, ul, li, a, i, txtValue;
    input = document.getElementById("myInput");
    input2 = document.getElementById("myInput2");

    filter = input.value.toLowerCase();
    filter2 = input2.value.toLowerCase();
    ul = document.getElementById("tableBody");
    li = ul.getElementsByClassName("myTr");
    for (i = 0; i < li.length; i++) {
        a = li[i].getElementsByClassName("invoiceNumber")[0];
        b = li[i].getElementsByClassName("table-no")[0];
        txtValue = a.textContent || a.innerText;
        txtValue2 = b.textContent || b.innerText;
        if (txtValue.toLowerCase().indexOf(filter) > -1) {
            console.log(a);
            $(a).closest("tr").show();
            if (txtValue2.toLowerCase().indexOf(filter2) > -1) {
                $(a).closest("tr").show();
            }
            else {

                $(a).closest("tr").hide();
            }
        } else {
            // li[i].style.display = "none";
            $(a).closest("tr").hide();
        }
    }

}

function continueCounting() {
    let gross_total = 0;
    $(".selected-items")
        .find(".added-item")
        .each(function () {
            let individual_pirce = Number(
                $(this).closest(".added-item").find(".item_total_price").text()
            );
            gross_total += individual_pirce;
        });
    $(".gross_amount").text(gross_total);
    gross_total = 0;
    calculateSum();
}
function deleteItem($this) {
    $($this).closest(".added-item").remove();
    let gross_total = 0;
    $(".selected-items")
        .find(".added-item")
        .each(function () {
            let individual_pirce = Number(
                $(this).closest(".added-item").find(".item_total_price").text()
            );
            gross_total += individual_pirce;
        });
    $(".gross_amount").text(gross_total);
    gross_total = 0;
    calculateSum();
}
// Increament
function increase($this) {
    $(document).ready(function () {
        let gross_total = 0;
        let qty = Number(
            $($this).closest(".added-item").find(".qty-box").val()
        );
        let added = qty + 1;
        $($this).closest(".added-item").find(".qty-box").val(added);
        $($this).closest(".added-item").find(".itemQty").val(added);
        let price = Number(
            $($this).closest(".added-item").find(".invoice_price").val()
        );
        $($this)
            .closest(".added-item")
            .find(".item_total_price")
            .text(price * added);
        $($this)
            .closest(".added-item")
            .find(".totalAmount")
            .val(price * added);

        $(".selected-items")
            .find(".added-item")
            .each(function () {
                let individual_pirce = Number(
                    $(this)
                        .closest(".added-item")
                        .find(".item_total_price")
                        .text()
                );
                gross_total += individual_pirce;
            });
        $(".gross_amount").text(gross_total);
        gross_total = 0;
        calculateSum();
    });
}

// Decreament
function decrease($this) {
    $(document).ready(function () {
        let gross_total = 0;
        let qty = Number(
            $($this).closest(".added-item").find(".qty-box").val()
        );
        if (qty <= 1) {
            qty = 2;
        }
        let added = qty - 1;
        $($this).closest(".added-item").find(".qty-box").val(added);
        $($this).closest(".added-item").find(".itemQty").val(added);
        let price = Number(
            $($this).closest(".added-item").find(".invoice_price").val()
        );
        $($this)
            .closest(".added-item")
            .find(".item_total_price")
            .text(price * added);
        $($this)
            .closest(".added-item")
            .find(".totalAmount")
            .text(price * added);

        $(".selected-items")
            .find(".added-item")
            .each(function () {
                let individual_pirce = Number(
                    $(this)
                        .closest(".added-item")
                        .find(".item_total_price")
                        .text()
                );
                gross_total += individual_pirce;
            });
        $(".gross_amount").text(gross_total);
        gross_total = 0;
        calculateSum();
    });
}
function recalculation($this) {
    $(document).ready(function () {
        let gross_total = 0;
        let qty = Number(
            $($this).closest(".added-item").find(".qty-box").val()
        );
        $($this).closest(".added-item").find(".qty-box").val(qty);
        $($this).closest(".added-item").find(".itemQty").val(qty);
        let price = Number(
            $($this).closest(".added-item").find(".invoice_price").val()
        );
        $($this)
            .closest(".added-item")
            .find(".item_total_price")
            .text(price * qty);
        $($this)
            .closest(".added-item")
            .find(".totalAmount")
            .text(price * qty);

        $(".selected-items")
            .find(".added-item")
            .each(function () {
                let individual_pirce = Number(
                    $(this)
                        .closest(".added-item")
                        .find(".item_total_price")
                        .text()
                );
                gross_total += individual_pirce;
            });
        $(".gross_amount").text(gross_total);
        gross_total = 0;
        calculateSum();
    });
    
}
function GetAgentCommission() {
    
    
     let id = $("#agent_id").val();
    //  let urls = `/travelagent/commission/+id+" " `;
     let url = `${window.location.origin}/travelagent/commission/${id}`;
    
     
    //  console.info(url);
    

   
    $.ajax({
        type: "GET",
        url: url,
        data: {
            id: id
           
        },

        success:
            function (response) {
                if (response.success==200) {
                   $('.commission_persent').val(response.commission_persent);
                   calculateSum();
                  
                }
               
            },
        error: function (err) {
            console.log(err);
        },
    });

}
