// Configuration et fonctions CinetPay
function initCinetPay(commandeId, amount, description, customerData) {
    CinetPay.setConfig({
        apikey: '521006956621e4e7a6a3d16.70681548',
        site_id: '405886',
        notify_url: 'http://mondomaine.com/notify/',
        mode: 'PRODUCTION'
    });
    
    CinetPay.getCheckout({
        transaction_id: commandeId + '_' + Math.floor(Math.random() * 100000000).toString(),
        amount: amount,
        currency: 'XOF',
        channels: 'ALL',
        description: description,
        customer_name: customerData.name,
        customer_surname: customerData.name.split(' ')[0],
        customer_email: customerData.email,
        customer_phone_number: customerData.phone || '00000000',
        customer_address: customerData.address || 'Non spécifiée',
        customer_city: customerData.city || 'Non spécifiée',
        customer_country: 'CI',
        customer_state: 'CI',
        customer_zip_code: customerData.zip_code || '0000'
    });
    
    CinetPay.waitResponse(function(data) {
        console.log(data);
        if (data.status == "ACCEPTED") {
            fetch('#', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    commande_id: commandeId,
                    transaction_id: data.cpm_trans_id,
                    amount: amount,
                    status: data.status
                })
            })
            .then(response => response.json())
            .then(result => {
                Swal.fire({
                    icon: 'success',
                    title: 'Paiement réussi',
                    text: 'Votre paiement a été effectué avec succès',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.reload();
                });
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Paiement échoué',
                text: 'Votre paiement a échoué. Veuillez réessayer.',
                confirmButtonText: 'OK'
            });
        }
    });
    
    CinetPay.onError(function(data) {
        console.error(data);
        Swal.fire({
            icon: 'error',
            title: 'Erreur',
            text: 'Une erreur est survenue lors du paiement',
            confirmButtonText: 'OK'
        });
    });
}

// Initialisation des boutons de paiement
function setupCinetPayButtons() {
    document.querySelectorAll('.btn-payer').forEach(button => {
        button.addEventListener('click', function() {
            const commandeId = this.getAttribute('data-commande-id');
            const amount = parseFloat(this.getAttribute('data-amount'));
            const articleName = this.getAttribute('data-article-name');
            
            const customerData = {
                name: this.getAttribute('data-user-name'),
                email: this.getAttribute('data-user-email'),
                phone: this.getAttribute('data-user-phone')
            };
            
            Swal.fire({
                title: 'Confirmer le paiement',
                html: `Vous êtes sur le point de payer <strong>${amount.toLocaleString()} Fcfa</strong> pour la commande <strong>${articleName}</strong>`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Payer maintenant',
                cancelButtonText: 'Annuler'
            }).then((result) => {
                if (result.isConfirmed) {
                    initCinetPay(commandeId, amount, `Paiement pour ${articleName}`, customerData);
                }
            });
        });
    });
}

// Exposer les fonctions au scope global
window.CinetPayHelper = {
    init: initCinetPay,
    setupButtons: setupCinetPayButtons
};