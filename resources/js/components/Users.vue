<template>
    <div class="card mt-5">
      <div class="card-header">
        <h3 class="card-title">Users Table</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addNewUserModal">
              <i class="fa fa-user-plus"></i>
              Add New
            </button>
        </div>
      </div>
      <!-- /.card-header -->
      <div class="card-body p-0">
        <table class="table table-hover">
          <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Email</th>
              <th>Date</th>
              <th>Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(user, index) in users" :key="user.id">
              <td>{{ index+1 }}</td>
              <td>{{ user.name }}</td>
              <td>{{ user.email }}</td>
              <td>{{ user.created_at | formatDate }}</td>
              <td>
                  <button class="btn btn-primary" v-on:click="editUser(user.id)">
                    <i class="fa fa-edit"></i>
                    Edit
                  </button>
                  <button class="btn btn-danger">
                    <i class="fa fa-close"></i>
                    Delete
                  </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <!-- /.card-body -->
      <!-- Modal -->
        <div class="modal fade" id="addNewUserModal" tabindex="-1" role="dialog" aria-labelledby="addNewUserModalLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="addNewUserModalLabel">Add New User</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
                <form @submit.prevent="addUser" @keydown="form.onKeydown($event)">
                  <div class="modal-body">
                      <div class="form-group">
                        <label>Name</label>
                        <input v-model="form.name" type="text" name="name"
                          class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                        <has-error :form="form" field="name"></has-error>
                      </div>

                      <div class="form-group">
                        <label>Email</label>
                        <input v-model="form.email" type="email" name="email"
                          class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                        <has-error :form="form" field="email"></has-error>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button :disabled="form.add" type="submit" class="btn btn-success">Add</button>
                  </div>
                </form>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    export default {

        data(){

            return {

                users: {},

                form: new Form({

                    name: '',
                    email: '',

                })

            }

        },

        methods: {

            getUser(){

              axios.get('/api/get-user')

                  .then(response => {

                      this.users = response.data.users;

                      console.log(response.data.users);

                  })

                  .catch(error => {

                        console.log(error);

                  });

            },

            addUser(){

                this.$Progress.start();

                // Submit the form via a POST request
                axios.post('/api/add-new-user', {

                      name : this.form.name,

                      email: this.form.email

                })

                .then(response => {

                    this.$Progress.finish();

                    iziToast.success({
                          title: 'Success',
                          message: response.data.message,
                          position: 'topRight',
                    });

                    this.form.name = '';
                    this.form.email = '';

                    $('#addNewUserModal').appendTo("body").modal('hide');

                    this.getUser();

                })

                .catch(error => {

                    console.log(error);

                    iziToast.error({
                          title: 'Error',
                          message: error.message,
                          position: 'topRight',
                    });


                });
            },

            editUser: function (user_id){

                console.log(user_id);

            }
              
        },

        created(){

            this.getUser();

        }
            
    }
</script>
