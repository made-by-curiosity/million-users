export interface IUser {
    id: number,
    first_name: string,
    last_name: string,
    email: string,
    created_at: string,
    updated_at: string,
    address: IUserAddress
}

export interface IUserAddress {
    id: number,
    country: string,
    city: string,
    post_code: string,
    address: string,
}

export interface IPaginatorLinks {
    url: string,
    label: string,
    active: boolean
}