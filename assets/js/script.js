// Document ready function
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Payment gateway integration
    if (document.getElementById('paymentButton')) {
        document.getElementById('paymentButton').addEventListener('click', function(e) {
            e.preventDefault();
            
            const amount = document.getElementById('entryFee').value;
            const teamId = document.getElementById('teamId').value;
            const teamName = document.getElementById('teamName').value;
            
            fetch('../processes/payment_process.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'payment_amount=' + amount
            })
            .then(response => response.json())
            .then(data => {
                const options = {
                    key: 'YOUR_RAZORPAY_KEY_ID',
                    amount: data.amount,
                    currency: 'INR',
                    name: 'Cricket Tournament Manager',
                    description: 'Tournament Entry Fee',
                    image: '../assets/images/logo.png',
                    order_id: data.order_id,
                    handler: function(response) {
                        // Handle payment success
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '../processes/payment_process.php';
                        
                        const paymentId = document.createElement('input');
                        paymentId.type = 'hidden';
                        paymentId.name = 'razorpay_payment_id';
                        paymentId.value = response.razorpay_payment_id;
                        form.appendChild(paymentId);
                        
                        const orderId = document.createElement('input');
                        orderId.type = 'hidden';
                        orderId.name = 'razorpay_order_id';
                        orderId.value = response.razorpay_order_id;
                        form.appendChild(orderId);
                        
                        const signature = document.createElement('input');
                        signature.type = 'hidden';
                        signature.name = 'razorpay_signature';
                        signature.value = response.razorpay_signature;
                        form.appendChild(signature);
                        
                        document.body.appendChild(form);
                        form.submit();
                    },
                    prefill: {
                        name: teamName,
                        email: 'team@example.com',
                        contact: '9999999999'
                    },
                    theme: {
                        color: '#007bff'
                    }
                };
                
                const rzp = new Razorpay(options);
                rzp.open();
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Payment processing failed. Please try again.');
            });
        });
    }

    // Challenge submission
    if (document.getElementById('challengeForm')) {
        document.getElementById('challengeForm').addEventListener('submit', function(e) {
            const challengedId = document.getElementById('challenged_id').value;
            const matchDate = document.getElementById('match_date').value;
            
            if (!challengedId || !matchDate) {
                e.preventDefault();
                alert('Please select a team and match date');
            }
        });
    }

    // Profile picture preview
    if (document.querySelector('input[type="file"][name="profile_pic"]')) {
        document.querySelector('input[type="file"][name="profile_pic"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.querySelector('.img-fluid.rounded-circle').src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }

    // Team logo preview
    if (document.querySelector('input[type="file"][name="logo"]')) {
        document.querySelector('input[type="file"][name="logo"]').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    document.querySelector('.img-fluid.rounded-circle').src = event.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});