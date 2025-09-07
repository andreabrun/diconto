
function loadExpenses(user, editable) {
    const expensesPeriodElement = document.getElementById("expensesPeriod");
    const expensesPeriod = expensesPeriodElement ? expensesPeriodElement.value : "last_settlement";
    
    fetch("func/expense/fetch_expenses.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "user=" + encodeURIComponent(user)
            + "&editable=" + encodeURIComponent(editable)
            + "&expensesPeriod=" + encodeURIComponent(expensesPeriod) 
    }).then(res => res.text())
    .then(data => {
        document.getElementById("expensesDiv").innerHTML = data;
    });

}

function loadOtherExpenses() {
    const otheruser = document.getElementById("otheruser").value;
    const expensesPeriodElement = document.getElementById("expensesPeriod");
    const expensesPeriod = expensesPeriodElement ? expensesPeriodElement.value : "last_settlement";
    fetch("func/expense/fetch_expenses.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "user=" + encodeURIComponent(otheruser)
            + "&editable=" + encodeURIComponent(false)
            + "&expensesPeriod=" + encodeURIComponent(expensesPeriod)
    }).then(res => res.text())
    .then(data => {
        document.getElementById("otheruserexpensesDiv").innerHTML = data;
    });
}

function addExpense(user) {
    const label = document.getElementById("label").value;
    const amount = document.getElementById("amount").value;
    const date = document.getElementById("date").value;

    fetch("func/expense/add_expense.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "label=" + encodeURIComponent(label) 
            + "&amount=" + encodeURIComponent(amount) 
            + "&date=" + encodeURIComponent(date)
    }).then(() => {
        loadExpenses(user, true);
        document.getElementById("label").value="";
        document.getElementById("amount").value="";
    });
}

function loadUsers() {
    fetch("func/user/fetch_users.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: ""
    }).then(res => res.text())
    .then(data => {
        document.getElementById("usersDiv").innerHTML = data;
    });
}

function addUser() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;
    const role = document.getElementById("role").value;

    fetch("func/user/add_user.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "username=" + encodeURIComponent(username) 
            + "&password=" + encodeURIComponent(password)
            + "&role=" + encodeURIComponent(role)
    }).then(() => {
        loadUsers();
    });
}

function deleteUser(uid) {
    fetch("./func/user/delete_user.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "uid=" + encodeURIComponent(uid)
    }).then(() => {
        loadUsers();
    });
}

function deleteExpense(eid, user) {
    fetch("func/expense/delete_expense.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "eid=" + encodeURIComponent(eid)
    }).then(() => {
        loadExpenses(user, true);
    });
}

function selectUser(username) {
    document.getElementById("selectedUser").value = username;
    document.getElementById("usernameDisplay").innerText = username;
    document.getElementById("password").value = "";
    document.getElementById("passwordForm").classList.remove("hidden");
}

function backToUserList() {
    document.getElementById("selectedUser").value = "";
    document.getElementById("usernameDisplay").innerText = "";
    document.getElementById("password").value = "";
    document.getElementById("passwordForm").classList.add("hidden");
}


function addLoan(user) {
    const label = document.getElementById("loanlabel").value;
    const amount = document.getElementById("loanamount").value;
    const date = document.getElementById("loandate").value;
    const userb = document.getElementById("loanuserb").value;

    fetch("func/loan/add_loan.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "label=" + encodeURIComponent(label) 
            + "&amount=" + encodeURIComponent(amount) 
            + "&date=" + encodeURIComponent(date)
            + "&userb=" + encodeURIComponent(userb)
    }).then(() => {
        loadLoans(user, true);
        document.getElementById("loanlabel").value="";
        document.getElementById("loanamount").value="";
    });
}

function loadLoans(user, editable) {
    const otheruser = document.getElementById("selectuserb").value;
    const loansPeriodElement = document.getElementById("loansPeriod");
    const loansPeriod = loansPeriodElement ? loansPeriodElement.value : "last_settlement";
    
    fetch("func/loan/fetch_loans.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "user=" + encodeURIComponent(user)
            + "&editable=" + encodeURIComponent(editable)
            + "&loansPeriod=" + encodeURIComponent(loansPeriod) 
            + (otheruser == "0" ? "" : "&userb=" + encodeURIComponent(otheruser))
    }).then(res => res.text())
    .then(data => {
        document.getElementById("loansDiv").innerHTML = data;
    });

}

function deleteLoan(eid, user) {
    fetch("func/loan/delete_loan.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "eid=" + encodeURIComponent(eid)
    }).then(() => {
        loadLoans(user, true);
    });
}

function loadSettlements(user) {
    fetch("func/settlement/fetch_settlements.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'}, 
        body: "user=" + encodeURIComponent(user)
    }).then(res => res.text())
    .then(data => {
        document.getElementById("settlementsDiv").innerHTML = data;
    });
}

function addSettlement() {
    const date = document.getElementById("settlementdate").value;
    fetch("func/settlement/add_settlement.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "date=" + encodeURIComponent(date)
    }).then(() => {
        loadSettlements();
    });
}

function deleteSettlement(id) {
    fetch("func/settlement/delete_settlement.php", {
        method: "POST",
        headers: {'Content-Type':'application/x-www-form-urlencoded'},
        body: "id=" + encodeURIComponent(id)
    }).then(res => res.text())
    .then(data => {
        alert(data);
        loadSettlements();
    });

}

function diconto(sid, user) {
    var currentView = getCurrentView();
    var selectedRows = getSelectedRows();
    if(selectedRows.length > 0) {
        let result = confirm("Are you sure you want mark these " + currentView  + ": [" + selectedRows + "] as settled?");
        if (result) {
            fetch("func/diconto/diconto.php", {
                method: "POST",
                headers: {'Content-Type':'application/x-www-form-urlencoded'},
                body: JSON.stringify({ type: currentView, rows: selectedRows, sid: sid})
            }).then(() => {
                if(currentView == 'expenses') {
                    loadExpenses(user, true);
                }
                else if(currentView == 'loans') {
                    loadLoans(user, true);
                }
            });
        }
    } else {
        alert("No rows selected!");
    } 
}
