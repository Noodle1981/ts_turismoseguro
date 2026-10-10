import type { components } from './schema'

export type UserResource = components['schemas']['UserResource']
export type LoginPayload = components['schemas']['LoginPayload']
export type RegisterRentPayload = components['schemas']['RegisterRentPayload']
export type RegisterClientPayload = components['schemas']['RegisterClientPayload']
export type ForgotPasswordPayload = components['schemas']['ForgotPasswordPayload']
export type ResetPasswordPayload = components['schemas']['ResetPasswordPayload']
export type AuthResponse = components['schemas']['AuthResponse']
export type UserRole = UserResource['role']
