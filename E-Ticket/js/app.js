const app = new Vue({
    el: '#app',
    data: {
      firstName: '',
      lastName: '',
      email: '',
      ticketQuantity: 1,
      ticketType: 'general',
      referrals: [],
      specialRequests: '',
      signature: '',
      purchaseAgreementSigned: false,
      requiredFieldClass: 'required',
    },
    computed: {
      fullName: {
        get: function() {
          if (this.firstName && this.lastName) {
            return this.firstName + ' ' + this.lastName;
          } else {
            return this.firstName || this.lastName;
          }
        },
        set: function(newFullName) {
          const names = newFullName.split(' ');
  
          if (names.length === 2) {
            this.firstName = names[0];
            this.lastName = names[1];
          }
          
          if (names.length <= 1) {
            this.firstName = names[0] || '';
            this.lastName = '';
          }
        }
      },
      ticketDescription: function() {
        let readableTicketType = 'General Admission';
        if (this.ticketType === 'vip') {
          readableTicketType = 'VIP';
        }
  
        let ticketPluralization = 'tickets';
        if (this.ticketQuantity === 1) {
          ticketPluralization = 'ticket';
        }
  
        return this.ticketQuantity + ' ' + readableTicketType + ' ' + ticketPluralization;
      },
      emailIsValid: function() {
        return this.email.includes('@');
      },
      formIsValid: function() {        
        return this.firstName && this.lastName && this.emailIsValid && this.purchaseAgreementSigned;
      },
      emailClasses: function() {
        return {
          touched: this.email.length !== 0,
          invalid: this.email && !this.emailIsValid
        };
      }
    },
    watch: {
      specialRequests: function(newRequests, oldRequests) {
        if (newRequests.toLowerCase().includes('meet and greet') || 
            newRequests.toLowerCase().includes('meet-and-greet')) {
          this.ticketType = 'vip';
        }
      }
    },
    methods: {
      resetFields: function() {
        this.firstName = '';
        this.lastName = '';
        this.email = '';
        this.ticketQuantity = 1;
        this.ticketType = 'general';
        this.referrals = [];
        this.specialRequests = '';
        this.signature = '',
        this.purchaseAgreementSigned = false;
      }, 
      handleSubmit: function() {
        let self = this;
        let obj = {};
        obj.firstName = this.firstName;
        obj.lastName = this.lastName;
        obj.email = this.email;
        obj.ticketQuantity = this.ticketQuantity;
        obj.ticketType = this.ticketType;
        obj.referrals = JSON.stringify(this.referrals);
        obj.specialRequests = this.specialRequests;
        let isSigned = this.purchaseAgreementSigned ? 1 : 0;
        obj.purchaseAgreementSigned = isSigned;
        let result = JSON.stringify(obj);
        let postUrl = "ticket-api/ticket/createTicket.php";
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200 || this.status == 201) {
              self.resetFields();
            }
        };
        xhttp.open("POST", postUrl, true);
        xhttp.send(result);
      }
    }
  });