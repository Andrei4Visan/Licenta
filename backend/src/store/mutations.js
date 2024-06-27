export function setUser(state, user){
    state.user.data = user;

}

export function setToken(state, token){
    state.user.token = token;
    if(token){
        sessionStorage.setItem('TOKEN', token);
    }else{
        sessionStorage.removeItem('TOKEN');
    }

}



export function setProducts(state,[loading, response= null]){
    if(response){
        state.products = {
            // ...state.products,
            data: response.data,
            links: response.meta.links,
            total: response.meta.total,
            limit: response.meta.per_page,
            from: response.meta.from,
            to: response.meta.to,
            current_page: response.meta.current_page,
        }
    }
    state.products.loading = loading;
    // state.products.data = response.data;
    // state.products.links = response.links
}

export function setOrders(state,[loading, response= null]){
    if(response){
        state.orders = {
            data: response.data,
            links: response.meta.links,
            total: response.meta.total,
            limit: response.meta.per_page,
            from: response.meta.from,
            to: response.meta.to,
            current_page: response.meta.current_page,
        }
    }
    state.orders.loading = loading;

}

export function setUsers(state,[loading, response= null]){
    if(response){
        state.users = {
            // ...state.products,
            data: response.data,
            links: response.meta.links,
            total: response.meta.total,
            limit: response.meta.per_page,
            from: response.meta.from,
            to: response.meta.to,
            current_page: response.meta.current_page,
        }
    }
    state.users.loading = loading;
    // state.products.data = response.data;
    // state.products.links = response.links
}

export function setCustomers(state,[loading, response= null]){
    if(response){
        state.customers = {
            // ...state.products,
            data: response.data,
            links: response.meta.links,
            total: response.meta.total,
            limit: response.meta.per_page,
            from: response.meta.from,
            to: response.meta.to,
            current_page: response.meta.current_page,
        }
    }
    state.users.loading = loading;
    // state.products.data = response.data;
    // state.products.links = response.links
}

export function setCountries(state, countries){
    state.countries = countries.data;
}


